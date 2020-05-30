<?php
define("NO_ROUTE", true);
include("../../index.php");
set_time_limit(5);

define("OUTPUT_ORDERS_DIR", PRESETS_DIR."/orders");

if (sizeof($_POST)) {

    $output = null;

    $commands = explode(" ", $_POST["command"]);
    switch ($commands[0]) {
        case "download":
            if ($commands[1] == "wanikani")
                $output = download_wanikani($_POST["api_key"]);
            break;
        case "make":
            if ($commands[1] == "default")
                $output = make_default_set();
            break;
        case "order":
            $output = make_ordered_set($commands[1], $commands[3]);
            break;
        case "sectionate":
            $output = make_sectionated_set($commands[1]);
            break;
        case "reset":
            $output = hard_reset();
            break;
    }
    print ($output ?? "No commands run");
    exit;
}

function hard_reset() {
    $output = "Hard Reset ----".PHP_EOL;
    $orders = array_diff(scandir(OUTPUT_ORDERS_DIR), [".",".."]);
    $orders_to_keep = [
        "frequency.txt",
        "heisig.txt",
        "jlpt.txt",
        "jouyou.txt",
        "kanken.txt",
        "kyouiku.txt",
    ];
    $orders = array_diff($orders, $orders_to_keep);
    if (sizeof($orders)) {
        $output .= "Removing:".PHP_EOL;
        foreach ($orders as $order) {
            $output .= " - $order" . PHP_EOL;
            unlink(OUTPUT_ORDERS_DIR."/$order");
        }
    }
    else {
        $output .= "No files to remove";
    }
    return trim($output);
}

function download_wanikani($api_key) {

    $all_kanji = [];

    $endpoint = "subjects?types=kanji";
    while ($endpoint != "") {
        $response = wanikani_request($endpoint, $api_key, true);
        if (!isset($response["data"])) die("Error with WK request: " . print_r($response, true));
        $endpoint = endpoint_from_url($response["pages"]["next_url"]);
        foreach ($response["data"] as $kanji) {
            $all_kanji[] = [
                "kanji" => $kanji["data"]["characters"],
                "meaning" => $kanji["data"]["meanings"][0]["meaning"],
                "level" => intval($kanji["data"]["level"]),
            ];
        }
    }

    usort($all_kanji, function($a,$b){
        return strcmp($a["meaning"],$b["meaning"]);
    });

    // Start Output
    $output = "Source - https://www.wanikani.com/kanji";
    $output .= PHP_EOL . PHP_EOL;

    // Output by level
    for ($level = 1; $level <= 60; $level++) {
        $output .= "Level $level: ";
        if ($level < 10) $output .= " ";
        foreach ($all_kanji as $kanji) {
            if ($kanji["level"] == $level)
                $output .= $kanji["kanji"];
        }
        $output .= PHP_EOL;
    }

    file_put_contents(OUTPUT_ORDERS_DIR."/wanikani.txt", trim($output));
    return $output;
}

function make_default_set() {
    // How to make default set
    // 1. Start with Heisig
    $default_set = load_kanji_array_from_set("heisig");

    // 2. Add in all missing WK kanji
    $wk_kanji = load_kanji_array_from_set("wanikani");
    foreach ($wk_kanji as $kanji) {
        if (!in_array($kanji, $default_set)) {
            $heisig_kanji[] = $kanji;
            //print "Adding $kanji" . PHP_EOL;
        }
    }

    // 3. Remove all non-WK kanji
    foreach ($default_set as $k=>$kanji) {
        if (!in_array($kanji, $wk_kanji)) {
            unset($default_set[$k]);
            //print "Removing $kanji" . PHP_EOL;
        }
    }

    // Start Output
    $source = "Source - (see Heisig ordering and WaniKani ordering)";

    return output_basic_set_file("default", $source, $default_set);
}

function make_ordered_set($base_set, $order_set) {

    $ordered_kanji = load_kanji_array_from_set($order_set);
    $sort_function = function($a, $b) use ($ordered_kanji) {
        $aa = array_search($a, $ordered_kanji, true);
        $bb = array_search($b, $ordered_kanji, true);
        if ($aa === $bb) return 0;
        if ($aa === false) return 1;
        if ($bb === false) return -1;
        return $aa <=> $bb;
    };

    $sections = load_sections_from_set($base_set);
    foreach ($sections as &$section) {
        usort($section, $sort_function);
    }

    $output_file_name = "$base_set-ordered-by-$order_set";
    $source = load_source_from_set($base_set);

    if (sizeof($sections) == 1) {
        $kanji = array_shift($sections);
        return output_basic_set_file($output_file_name, $source, $kanji);
    }
    else {
        return output_sectioned_set_file($output_file_name, $source, $sections);
    }
}

function join_array_of_arrays($array_of_arrays) {
    $return = [];
    foreach ($array_of_arrays as $array) {
        $return = array_merge($return, $array);
    }
    return $return;
}

function make_sectionated_set($name) {
    // This function is only meant to be used for WaniKani sets
    // It groups the levels by Pleasant / Painful / Death / Hell / Paradise / Reality
    $sections = load_sections_from_set($name);
    $sections = [
        "快 Pleasant" => join_array_of_arrays(array_slice($sections, 0, 10)),
        "苦 Painful" => join_array_of_arrays(array_slice($sections, 10, 10)),
        "死 Death" => join_array_of_arrays(array_slice($sections, 20, 10)),
        "地獄 Hell" => join_array_of_arrays(array_slice($sections, 30, 10)),
        "天国 Paradise" => join_array_of_arrays(array_slice($sections, 40, 10)),
        "現実 Reality" => join_array_of_arrays(array_slice($sections, 50, 10)),
    ];
    $new_name = str_replace("wanikani","wanikani-sections",$name);
    return output_sectioned_set_file($new_name, load_source_from_set($name), $sections);
}

function output_basic_set_file($name, $source, $kanji) {

    $output = $source;
    $output .= PHP_EOL . PHP_EOL;

    // Output in groups of 250
    $group_i = 0;
    $groups = array_chunk($kanji, 250);
    foreach ($groups as $group) {
        $output .= "# ".($group_i+1)." - ".($group_i + sizeof($group));
        $output .= PHP_EOL;
        // Output in lines of 50
        $lines = array_chunk($group, 50);
        foreach ($lines as $line) {
            // Output in chunks of 10
            $chunks = array_chunk($line, 10);
            $output .= implode(" ",array_map("implode",$chunks));
            $output .= PHP_EOL;
        }
        $output .= PHP_EOL;

        $group_i += sizeof($group);
    }

    file_put_contents(OUTPUT_ORDERS_DIR."/$name.txt", trim($output));
    return $output;
}

function output_sectioned_set_file($name, $source, $sections) {
    $output = $source;
    $output .= PHP_EOL . PHP_EOL;

    $condensed = sizeof($sections) > 30;

    foreach ($sections as $section_header=>$kanji) {
        $output .= $section_header . ":";
        if ($condensed) {
            $output .= " ";
            $output .= implode($kanji);
            $output .= PHP_EOL;
        }
        if (!$condensed) {
            $output .= PHP_EOL . PHP_EOL;
            $output .= "# " . sizeof($kanji) . " Kanji";
            $output .= PHP_EOL;
            $output .= implode(PHP_EOL, mb_str_split(implode($kanji), 20));
            $output .= PHP_EOL;
            $output .= PHP_EOL;
        }
    }

    file_put_contents(OUTPUT_ORDERS_DIR."/$name.txt", trim($output));
    return $output;
}

function load_kanji_array_from_set($set) {
    $contents = file_get_contents(OUTPUT_ORDERS_DIR."/$set.txt");
    $contents = remove_ascii_characters($contents);
    return mb_str_split($contents);
}
function load_source_from_set($set) {
    return trim(file(OUTPUT_ORDERS_DIR."/$set.txt")[0]);
}

function load_sections_from_set($set) {
    $section_header = "";
    $kanji_by_sections = [];
    $lines = file(OUTPUT_ORDERS_DIR."/$set.txt");
    array_shift($lines);
    foreach ($lines as $line) {
        $line = trim($line);
        if (mb_substr($line,0,1) == "#")
            continue;
        if ($colon_pos = mb_strpos($line, ":")) {
            $section_header = mb_substr($line, 0, $colon_pos);
            $line = mb_substr($line, $colon_pos+1);
        }
        $line = remove_ascii_characters($line);
        if (strlen($line)) {
            if (!isset($kanji_by_sections[$section_header]))
                $kanji_by_sections[$section_header] = "";
            $kanji_by_sections[$section_header] .= $line;
        }
    }
    array_walk($kanji_by_sections, function(&$v){
        $v = mb_str_split($v);
    });
    return $kanji_by_sections;
}

function remove_ascii_characters($str) {
    return preg_replace("/[\x01-\x7f]/u","",$str);
}

?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <script src="<?=ROOT_URL?>/public/3rd-party/jquery-3.5.1.min.js"></script>
        <style>
        body {
            background-color: black;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: Arial, sans-serif;
        }
        #container {
            padding: 20px;
            background-color: #333;
            width: 800px;
        }
        #top-pane {
            height: 50vh;
            overflow-y:auto;
            border: 2px outset grey;
            padding: 10px;
        }
        </style>
    </head>
    <body>

    <div id="container" style="display: flex; flex-direction: column;">
        <div id="top-pane">
            <h2>WaniKani</h2>
            <label>API Key: <input id="api-key" type="text"></label>
            <button data-command="download wanikani">Download from WaniKani</button>

            <h2>Heisig</h2>
            <p>Update manually</p>

            <h2>Default Set</h2>
            <button data-command="make default">Make Default Set</button>

            <h2>Frequency</h2>
            <p>Update manually</p>

            <h1>WaniKani Subsets</h1>
            Order:
            <button data-command="order wanikani by heisig">by Heiseg</button>
            <button data-command="order wanikani by frequency">by Frequency</button>
            <h2>WaniKani Section Subsets</h2>
            Sectionate:
            <button data-command="sectionate wanikani">WaniKani</button>
            <button data-command="order wanikani-sections by heisig">Ordered by Heisig</button>
            <button data-command="order wanikani-sections by frequency">Ordered by Frequency</button>
            (Each 10 levels are sectionated)


            <h1>JLPT Levels</h1>
            <p>Update base set manually</p>
            <p>Subsets:</p>
            <button data-command="order jlpt by wanikani">order by WaniKani</button>
            <button data-command="order jlpt by heisig">order by Heisig</button>
            <button data-command="order jlpt by frequency">order by Frequency</button>

            <h1>Kanken Levels</h1>
            <p>Update base set manually</p>
            <p>Subsets:</p>
            <button data-command="order kanken by wanikani">order by WaniKani</button>
            <button data-command="order kanken by heisig">order by Heisig</button>
            <button data-command="order kanken by frequency">order by Frequency</button>

            <h1>Jouyou Levels</h1>
            <p>Update base set manually</p>
            <p>Subsets:</p>
            <button data-command="order jouyou by wanikani">order by WaniKani</button>
            <button data-command="order jouyou by heisig">order by Heisig</button>
            <button data-command="order jouyou by frequency">order by Frequency</button>

            <h1>Kyouiku Levels</h1>
            <p>Update base set manually</p>
            <p>Subsets:</p>
            <button data-command="order kyouiku by wanikani">order by WaniKani</button>
            <button data-command="order kyouiku by heisig">order by Heisig</button>
            <button data-command="order kyouiku by frequency">order by Frequency</button>

            <h1>Reset</h1>
            <p>This will delete everything except frequency, heisig, jlpt, jouyou, kanken, kyouiku</p>
            <button disabled data-command="reset">Reset</button>
            <div ondblclick="document.querySelector('button:disabled').disabled=false">
                Double-click this text to enable button
            </div>


        </div>
        <pre id="response"></pre>
    </div>

    <script>
    $(()=>{
        $("button").on("click",e=>{
            let $e = $(e.currentTarget);
            $e.attr("disabled",true);
            $.post(document.location.href, {
                api_key: $("#api-key").val(),
                command: $e.attr("data-command"),
            }, resp=>{
                $e.css("background-color","#0c0");
                $e.attr("disabled",false);
                $("#response").text(resp);
            });
        });
    });
    </script>

    </body>
    </html>
<?php


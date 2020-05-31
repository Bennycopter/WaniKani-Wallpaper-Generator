<?php require "../password-protect.php";

define("NO_ROUTE",true);
include "../../index.php";

if (sizeof($_POST)) {
    switch ($_POST["action"]) {
        case "get-users-by-day":
            print generations_by_user_on_day($_POST["day"]);
            exit;
    }
}

function all_generations() {
    $generations_per_day = [];

    // Load all generations
    $user_folders = array_diff(scandir(USERS_DIR),[".",".."]);
    foreach ($user_folders as $user_folder) {
        if (!is_dir(USERS_DIR."/".$user_folder))
            continue;
        $generations = explode(PHP_EOL, file_get_contents(USERS_DIR."/$user_folder/generations.txt"));
        foreach ($generations as $generation) {
            if  (trim($generation) != "") {
                $pieces = explode("|",$generation);
                $time = $pieces[0];
                $date = date("Y-m-d", $time);
                if (!isset($generations_per_day[$date]))
                    $generations_per_day[$date] = 0;
                $generations_per_day[$date]++;
            }
        }
    }

    // Print generations
    krsort($generations_per_day);
    return $generations_per_day;
}

function generations_by_user_on_day($day) {
    $generations_per_user = [];

    // Load all generations
    $user_folders = array_diff(scandir(USERS_DIR),[".",".."]);
    foreach ($user_folders as $user_folder) {
        if (!is_dir(USERS_DIR."/".$user_folder))
            continue;

        $username = file_get_contents(USERS_DIR."/".$user_folder."/username.txt");

        $generations = explode(PHP_EOL, file_get_contents(USERS_DIR."/$user_folder/generations.txt"));
        foreach ($generations as $generation) {
            if  (trim($generation) != "") {
                $pieces = explode("|",$generation);
                $time = $pieces[0];
                $date = date("Y-m-d", $time);

                if ($date == $day) {
                    if (!isset($generations_per_user[$user_folder]))
                        $generations_per_user[$user_folder] = [
                            "generations"=>0,
                            "username" => $username,
                        ];
                    $generations_per_user[$user_folder]["generations"]++;
                }
            }
        }
    }

    // Print generations
    arsort($generations_per_user);

    $output = "";
    foreach ($generations_per_user as $api_key=>$data) {
        $output .= $api_key . " - " . $data["generations"] . " - " . $data["username"] . PHP_EOL;
    }
    return $output;
}

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
    body {
        min-height: 100vh;
        display: flex;
        margin: 0;
        justify-content: center;
        align-items: center;
        background-color: black;
        color: white;
    }
    #container {
        width: 1000px;
        display: flex;
        height: 80vh;
    }
    #left {
        display: grid;
        grid-template-columns: auto auto;
        width: 200px;
        background-color: #444;
        grid-gap: 2px;
        height: 100%;
        overflow-y: auto;
        justify-items: center;
        flex-shrink: 0;
    }
    #right {
        background-color: #222;
        flex-grow: 1;
        padding: 15px;
    }
    .clickable {
        cursor: pointer;
    }
    .clickable:hover {
        color: #10cafe;
    }
    </style>
    <script src="<?=ROOT_URL?>/public/3rd-party/jquery-3.5.1.js"></script>
    <script>
    $(()=>{
        $(".clickable").on("click",e=>{
            $.post(window.location.href,
                {
                    action:"get-users-by-day",
                    day: $(e.currentTarget).attr("data-day")
                },data=>{
                    $("#right pre").text(data);
                })
        });
    });
    </script>
</head>
<body>

<div id="container">
    <div id="left">
        <?php foreach(all_generations() as $day=>$n): ?>
            <div class="clickable" data-day="<?=$day?>"><?=$day?></div>
            <div class="clickable"><?=$n?></div>
        <?php endforeach; ?>
    </div>
    <div id="right"><pre></pre></div>
</div>

</body>
</html>
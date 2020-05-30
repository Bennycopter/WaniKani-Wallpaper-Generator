<?php require "../password-protect.php";

// This file downloads the kanji from WaniKani
// and saves it to /data/all-wk-kanji.txt
// This should be run each time WaniKani has a content update

if (!isset($_GET["api_key"]))
    die("Put <pre>?api_key=____</pre> in the URL");

define("NO_ROUTE",true);
include("../../index.php");

set_time_limit(5);

$api_key = $_GET["api_key"];

$all_kanji = [];

// Run through all endpoints
$endpoint = "subjects?types=kanji";
while ($endpoint != "") {
    print "Requesting - $endpoint<br>";
    $response = wanikani_request($endpoint, $api_key, true);
    if (!$response) die("Error with WK request");
    $endpoint = endpoint_from_url($response["pages"]["next_url"]);
    foreach ($response["data"] as $kanji_data) {
        $all_kanji[$kanji_data["id"]] = $kanji_data["data"]["characters"];
    }
}

file_put_json("../../presets/all-wk-kanji.txt", $all_kanji);

print "Here are the kanji that were found:";
print "<pre>";
print_r($all_kanji);
print "<pre>";
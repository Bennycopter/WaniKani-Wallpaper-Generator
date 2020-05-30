<?php defined("INDEX") or die;

function log_error($msg, $line, $file) {
    $filename = ERRORS_DIR . "/" . time() . rand(1000,9999) . ".txt";
    $ip = $_SERVER["REMOTE_ADDR"];
    file_put_contents(
        $filename,
        "$ip\n$msg\nLine $line in $file"
    );
}
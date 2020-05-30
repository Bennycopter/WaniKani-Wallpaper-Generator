<?php defined("INDEX") or die;

session_start();

if (isset($_GET["logout"])) {
    log_out_user();
    if (isset($_GET["api-key"]))
        header("Location: .?api-key=" . $_GET["api-key"]);
    else
        header("Location: .");
    exit;
}
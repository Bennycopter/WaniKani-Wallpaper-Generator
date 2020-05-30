<?php

// To add a password, add a folder to /secret/password with the format [a-zA-Z0-9\-]+
// Open /secret/password/ in the browser to generate a good one

session_start();

if (isset($_GET["password"]) && strlen($_GET["password"]))
    $_SESSION["password"] = $_GET["password"];

$password = $_SESSION["password"] ?? "";
$password = preg_replace("/[^a-zA-Z0-9\-]+/","",$password);

if (strlen($password) == 0 || !is_dir(__DIR__."/password/$password")) {
    header("HTTP/1.0 401 Unauthorized");
    die;
}
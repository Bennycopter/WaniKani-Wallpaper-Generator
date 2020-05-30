<?php defined("INDEX") or die;

// Compatibility links for old URLs
if (isset($_GET["_url"])) {
    $file = trim($_GET["_url"], "/");
    if ($file == 'download.php') {
        include PAGES_DIR . "/download.php";
        exit;
    }
    if ($file == 'order.php') {
        include PAGES_DIR . '/order.php';
        exit;
    }
}

// ?k=__&d=__ download URLs
if (isset($_GET["k"])) {
    include ("pages/download.php");
    exit;
}

// ?logout
if (isset($_GET["logout"])) {
    include ("pages/logout.php");
    exit;
}

session_start();

// Show login page
if (!isset($_SESSION["api-key"]) || isset($_POST["login"])) {
    include "pages/login.php";
    exit;
}

// Default: Settings page
include PAGES_DIR . "/settings.php";
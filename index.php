<?php

// Ready
define("INDEX",true);

// Aim
include "config.php";
include INCLUDES_DIR . "/helpers.php";
include INCLUDES_DIR . "/error_handling.php";
include INCLUDES_DIR . "/wanikani.php";
include INCLUDES_DIR . "/users.php";
include INCLUDES_DIR . "/presets.php";
include INCLUDES_DIR . "/post.php";
include INCLUDES_DIR . "/image.php";

// Fire
defined("NO_ROUTE") or include "router.php";
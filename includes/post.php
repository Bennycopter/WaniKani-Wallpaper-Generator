<?php defined("INDEX") or die;

// These functions help filter and validate $_POST input.  They are currently only used in pages/settings.php

function sanitize_filename($v) {
    // Removes backslashes, slashes, and double dots
    return preg_replace("/([\\\\\/]+|\.\.)/","", $v);
}
function filter_color_code($v) {
    return "#".preg_replace("/[^A-F0-9]/", "", strtoupper($v));
}
function validate_color_code($v) {
    return $v == filter_color_code($v) && strlen($v) == 7;
}

function filter_integer($v) {
    return intval(preg_replace("/[^0-9]/", "", $v));
}

function return_false() {
    return false;
}

function filter_checkbox_value($v) {
    return $v == "on" ? 1 : 0;
}
<?php

require_once("password-protect.php");

$tools = array_diff(scandir("tools"),[".",".."]);
print "<ul>";
foreach ($tools as $tool)
    print "<li><a href='tools/$tool'>$tool</a>";

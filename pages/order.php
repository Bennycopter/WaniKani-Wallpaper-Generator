<?php defined("INDEX") or die;

// Get file from URL
if (!isset($_GET["order"]))
    leave();

$order_file = PRESETS_DIR."/orders/" . str_replace("/","",$_GET["order"]) . ".txt";

if (!file_exists($order_file))
    leave();

$data = file_get_contents($order_file);

function leave() {
    //header("Location: .");
    //exit;
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
    <style>
    body {
        background-color: black;
        color: white;
        font-size: 16pt;
        display: flex;
        justify-content: center;
    }
    pre {
        white-space: pre-line;
    }
    </style>
</head>
<body>

<div id="container">
    <pre><?=$data?></pre>
</div>

</body>
</html>

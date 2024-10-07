<?php

$api_key = $_GET["api_key"];

if (sizeof($_POST)) {
    save_user_custom_kanji($api_key, $_POST["custom_kanji"]);
    print "OK, saved";
}

$custom_kanji = get_user_custom_kanji($api_key);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Kanji Manually</title>

    <style>
    body, textarea {
        background-color: black;
        color: white;
    }
    textarea {
        width: 100%;
        min-height: 400px;
    }
    pre {
        border: 1px solid white;
        width: 100px;
        padding: 1rem;
    }
    </style>
</head>
<body>

<p>Enter your kanji, one per line, followed by a space and then a number for its level.</p>

<ul>
    <li>Unseen = 0</li>
    <li>Apprentice = 1</li>
    <li>Guru = 2</li>
    <li>Master = 3</li>
    <li>Enlightened = 4</li>
    <li>Burned = 5</li>
</ul>

<p>Example:</p>
<pre>外 3
大 1
天 0
女 5
子 2
学 3</pre>

<form method="post" action="<?=$_SERVER["REQUEST_URI"]?>">
    <textarea name="custom_kanji" placeholder="Enter your custom kanji and levels here"><?=$custom_kanji?></textarea>
    <button>Save</button>
</form>

</body>
</html>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>&#65279;</title>
    <style>
    body {
        margin: 0;
        background-color: black;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        flex-direction: column;
    }
    #lenny {
        font-family: Arial, sans-serif;
        font-size: 160px;
        margin-bottom: 40px;
    }
    #sample-password {
        font-family: "Courier New", monospace;
        color: grey;
    }
    </style>
</head>
<body>
<div id="lenny">( ͡° ͜ʖ ͡°)</div>
<div>You thought this was a password, but it was me, Lenny!</div>
<div id="sample-password"><?php
    $characters = implode(array_merge(
        range("a","z"),
        range("A","Z"),
        range(0,9)
    ));
    for ($i = 0; $i < 50; $i++) {
        print $characters[rand(0, strlen($characters)-1)];
    }
    ?></div>
</body>
</html>
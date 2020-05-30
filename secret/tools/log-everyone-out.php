<?php require "../password-protect.php";

if (sizeof($_POST)) {
    $sessions_dir = "../../data/sessions";
    if (!is_dir($sessions_dir)) die("Directory doesn't exist $sessions_dir");
    $files = array_diff(scandir($sessions_dir),[".","..",".gitkeep"]);
    foreach ($files as $file)
        unlink($sessions_dir."/".$file);
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
</head>
<body>

<?php
if (isset($files)) {
    print "Erased " . sizeof($files) . " sessions";
}
?>

<form method="post">
    <button name="unko-bomb">Click to log everyone out</button>
</form>

</body>
</html>

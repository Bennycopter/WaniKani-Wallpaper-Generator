<?php require "../password-protect.php";

define("NO_ROUTE",true);
include("../../index.php");

$response = [];

if (sizeof($_POST)) {
	$response = wanikani_request($_POST["endpoint"], $_POST["api_key"], true);
}
?>
<style>
input {
	width: 300px;
}
</style>
<form method="post">
	<label>API Key: <input name="api_key" type="text" value="<?=$_POST['api_key']??""?>"></label><br>
	<label>Endpoint: <input name="endpoint" type="text" value="<?=$_POST['endpoint']??""?>"></label><br>
	<a href="https://docs.api.wanikani.com/">Documentation</a><br>
	<button>Go</button>
</form>
<pre><?=print_r($response, true)?></pre>

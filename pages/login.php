<?php defined("INDEX") or die;

/// Handle POST ////////////////////////////////////////////////////////////////////////////////////////////////////////

if (sizeof($_POST) > 0 && isset($_POST["api-key"]) && isset($_POST["device"])) {
    // The user is trying to log in

    $api_key = preg_replace("/[^a-zA-Z0-9\-]/", "", $_POST["api-key"]);
    $device = intval(preg_replace("/[^0-9]/", "", $_POST["device"]));

    // Find errors
    $errors = [];

    // Error: Incorrect key
    if (strlen($_POST["api-key"]) != 36) {
        $errors[] = "The personal access token needs to be
                     a 36-character alphanumeric string with
                     dashes (like this 12345678-abcd-ef00-1234-123456789abc).
                     To find your personal access token,
                     <a href='https://www.wanikani.com/settings/personal_access_tokens'
                     target='_blank'>click here</a>.";
    }

    // Error: Old key
    $settings_filename = USERS_DIR . "/$api_key/settings-$device.txt";
    if (strlen($_POST["api-key"]) == 32 && file_exists($settings_filename)) {
        $old_settings = print_r(unserialize(file_get_contents($settings_filename)), true);
        $errors[] = "You entered an old v1 API Key.
                     You'll need a new v2 API Key from here:
                     <a href='https://www.wanikani.com/settings/personal_access_tokens'
                     target='_blank'>click here</a>.<br><br>
                     P.s. Here are your old settings for device $device:
                     <pre>$old_settings</pre>";
    }

    // Error: Device number out of range
    if ($device != clamp($device,1,10)) {
        $errors[] = "This hack attempt was recorded.  Isn't 10 devices enough?  c'mon man";
        log_error(
            "This input was given for a device select: $_POST[device]",
            __LINE__, __FILE__
        );
    }



    if (sizeof($errors) == 0) {

        // Existing user
        if (user_folder_exists($api_key)) {
            log_in_user($api_key, $device);
        }

        // Unknown user
        else {
            $wk_response = wanikani_request("user", $api_key);
            if ($wk_response) {
                log_in_user($api_key, $device);
                prepare_user_folder($api_key, $wk_response["data"]["username"]);
            }
            else {
                $errors[] = "The access token provided did not load from WaniKani properly.
                             Are you sure you copied it correctly?";
                log_error("API request failed to load.", __LINE__, __FILE__);
            }
        }

        if (sizeof($errors) == 0) {
            header("Location: .");
            exit;
        }
    }
}

include ROOT_DIR."/secret/banner.php";

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WaniKani Wallpaper Generator</title>
    <link rel='stylesheet' href='<?=ROOT_URL?>/public/css/styles.css?<?=V?>' />
</head>
<body>

<div id="wrapper">
    <h1>WaniKani Wallpaper Generator</h1>

    <?php if (isset($errors) && sizeof($errors) > 0): ?>
        <ul style="color: red">
        <?php foreach ($errors as $error): ?>
            <li><?=$error?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif;?>

    <form method="post" action=".">
        <div style="width: 50%; margin: 0 auto;">
            <div>
                <div style="float: left; padding-top: 5px;"><label for="api-key">API Token:</label></div>
                <input type="text" name="api-key" placeholder="________-____-____-____________" style="width: 72%; float: right;" value="<?= $_GET["api-key"] ?? "" ?>" id="api-key" />
            </div>

            <div style="padding-top: 15px; clear: both;">

                <div style="float: left; padding-top: 9px;"><label for="device">Device:</label></div>
                <select style="width: 72%; float: right;" name="device" id="device">
                    <option>Device 1</option>
                    <option>Device 2</option>
                    <option>Device 3</option>
                    <option>Device 4</option>
                    <option>Device 5</option>
                    <option>Device 6</option>
                    <option>Device 7</option>
                    <option>Device 8</option>
                    <option>Device 9</option>
                    <option>Device 10</option>
                </select>
            </div>
        </div>

        <div style="clear: both; text-align: center; ">
            <button class="btn-big" style="margin-top: 20px;">Log In</button>
        </div>

    </form>
    <div style="clear: both; height: 0;"></div>
    <p style="text-align: center"><a href="https://www.wanikani.com/settings/personal_access_tokens" target="_blank">Where do I find my API Tokens?</a></p>
    <p style="text-align: center">Need help?  Check out the <a target="_blank" href="https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321">WK Community page</a></p>
</div>

</body>
</html>


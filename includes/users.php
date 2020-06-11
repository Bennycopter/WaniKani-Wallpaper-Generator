<?php defined("INDEX") or die;

function log_in_user($api_key, $device) {
    $_SESSION["api-key"] = $api_key;
    $_SESSION["device"] = $device;
}

function log_out_user() {
    unset($_SESSION["api-key"]);
    unset($_SESSION["device"]);
}

function get_user_settings($api_key,$device) {

    $settings = default_user_settings();
    $file = user_settings_file($api_key,$device);
    if (file_exists($file)) {
        $user_settings = file_get_json($file);
        foreach ($user_settings as $k=>$v)
            $settings[$k] = $v;
    }
    return $settings;
}

function get_user_username($api_key) {
    return trim(file_get_contents(user_username_file($api_key)));
}

function save_user_settings($api_key, $device, $settings) {
    file_put_json(user_settings_file($api_key,$device), $settings);
}

function user_settings_file($api_key, $device) {
    return USERS_DIR."/$api_key/settings-$device.txt";
}
function user_username_file($api_key) {
    return USERS_DIR."/$api_key/username.txt";
}

function default_user_settings() {
    return [
        // Presets
        "screen_preset" => "Default",
        "color_scheme" => "Default",
        "font"=>"Komorebi Gothic",
        "kanji_set" => "default",

        // Custom Color Schemes
        "c_background" => null,
        "c_unseen" => null,
        "c_apprentice" => null,
        "c_guru" => null,
        "c_master" => null,
        "c_enlightened" => null,
        "c_burned" => null,
        "c_section_titles" => null,
        "c_wallpaper_title" => null,

        // Custom Screen Dimensions
        "width" => null,
        "height" => null,
        "left" => null,
        "right" => null,
        "top" => null,
        "bottom" => null,

        // Kanji Subsets
        "first_kanji" => null,
        "last_kanji" => null,
        "first_section" => null,
        "last_section" => null,

        // Wallpaper title
        "show_wallpaper_title" => 0,
        "wallpaper_title_padding_top" => 0,
        "wallpaper_title_padding_left" => 0,
        "wallpaper_title_padding_bottom" => 10,
        "custom_title" => "",
        "wallpaper_title_height" => 50,
        "wallpaper_title_font" => "HonyaJi Re",

        // Section titles
        "show_section_titles" => 1,
        "collapse_sections" => 0,
        "section_title_padding_top" => 15,
        "section_title_padding_left" => 5,
        "section_title_padding_bottom" => 10,
        "section_title_height" => 15,
        "section_titles_font" => "Motoya Kusugi Maru",

        // Kanji Spacing
        "kanji_spacing" => 3,

        // Accents
        "c_apprentice_accent" => null,
        "c_guru_accent" => null,
        "c_master_accent" => null,
        "c_enlightened_accent" => null,
        "c_burned_accent" => null,
        "accent_time_apprentice" => null,
        "accent_time_guru" => null,
        "accent_time_master" => null,
        "accent_time_enlightened" => null,
        "accent_time_burned" => null,
    ];
}

function prepare_user_folder($api_key, $username) {

    // .../[api-key]
    $user_folder_dir = USERS_DIR."/".$api_key;
    if (!is_dir($user_folder_dir))
        mkdir($user_folder_dir);

    // .../[api-key]/username.txt
    file_put_contents($user_folder_dir."/username.txt", $username);

    // .../[api-key]/generations.txt
    touch($user_folder_dir."/generations.txt");

    // .../[api-key]/settings-__.txt
    for ($i=1; $i<=MAX_DEVICES; $i++) {
        $settings_file = $user_folder_dir . "/settings-$i.txt";
        if (!file_exists($settings_file)) {
            file_put_contents($settings_file, json_encode([]));
        }
    }
}

function user_folder_exists($api_key) {
    return file_exists(user_username_file($api_key));
}

function log_generation($api_key) {
    $log = time(). "|" . $_SERVER["REMOTE_ADDR"] . PHP_EOL;
    file_put_prepended($log, USERS_DIR."/$api_key/generations.txt");
}

function num_generations_today($api_key) {
    $yesterday = time() - 24 * 60 * 60;
    $num_generations = 0;

    $lines = file(USERS_DIR."/$api_key/generations.txt");
    foreach ($lines as $line) {
        $pieces = explode(":",$line);
        $time = intval($pieces[0]);
        if ($time <= $yesterday)
            return $num_generations;
        $num_generations++;
    }
    return $num_generations;
}

function get_user_progress_report($api_key) {
    $kanji_by_id = json_decode(file_get_contents(PRESETS_DIR."/all-wk-kanji.txt"),true);
    $progress_report = [];

    # API Call to WaniKani.com
    $endpoint = "assignments?subject_types=kanji&started=true";
    while ($endpoint != "") {

        $response = wanikani_request($endpoint, $api_key);
        if (!$response) {
            die_with_text_on_image("There was an issue with the API call to 'assignments'.  Please try again.\nIf this error persists, please let me know on the community page.");
        }
        $endpoint = endpoint_from_url($response["pages"]["next_url"]);

        foreach ($response["data"] as $assignment_data) {
            $stage = $assignment_data["data"]["srs_stage_name"];
            if (strpos($stage, " "))
                $stage = substr($stage, 0, strpos($stage, " "));
            $stage = strtolower($stage);
            $subject_id = $assignment_data["data"]["subject_id"];

            $progress_report[$kanji_by_id[$subject_id]] = $stage;
        }
    }

    return $progress_report;
}
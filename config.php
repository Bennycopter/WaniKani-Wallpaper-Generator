<?php defined("INDEX") or die;

# Version
define("V", "2020-05-29" . ($_SERVER["HTTP_HOST"]=="localhost"?time():""));

# Timezone
date_default_timezone_set("UTC");

# Directories
define("ROOT_DIR", __DIR__); // E.g. C:\xampp\htdocs\mmm
define("ASSETS_DIR", ROOT_DIR . "/assets");
define("DATA_DIR", ROOT_DIR . "/data");
define("ERRORS_DIR", DATA_DIR . "/errors");
define("SESSIONS_DIR", DATA_DIR . "/sessions");
define("USERS_DIR", DATA_DIR . "/users");
define("INCLUDES_DIR", ROOT_DIR . "/includes");
define("PAGES_DIR", ROOT_DIR . "/pages");
define("PRESETS_DIR", ROOT_DIR . "/presets");

# Sessions
if (session_status() != PHP_SESSION_ACTIVE)
    session_save_path(SESSIONS_DIR);

# URLs
define("SITE_URL", "http://wkw.natural20design.com/");
define("ROOT_URL",
    $_SERVER["HTTP_HOST"]=="localhost"
        ? '/'.pathinfo(__DIR__, PATHINFO_BASENAME)."/"
        : ""
);

# Assets
define("SSL_CERT", ASSETS_DIR . "/cacert-2020-01-01.pem");

# Other Config
define("MAX_DEVICES", 10);
define("COMMUNITY_TOPIC_URL", "https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321");
define("CURL_FOR_WINDOWS_VERSION", "7.70.0");
define("MAX_FONT_SCALE", 0.80);
define("MIN_FONT_SCALE", 0.50);
define("DAILY_GENERATION_LIMIT", 100);
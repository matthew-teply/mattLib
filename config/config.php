<?php
# Roots
define("ROOT", "/mattLib2/"); // Directory root for your application (/data/www/myexapleapp)
define("WEB_SERVER", "/mattLib2/"); // Web server address (https://www.example.com)

define("DEFAULT_APP", "website");

# Check if first parameter of URL is an app name
if(isset($_GET['url']) && is_dir("app/" . explode("/", $_GET['url'])[0])) {
    $_GET['app'] = explode("/", $_GET['url'])[0];
    $_GET['url'] = explode("/", $_GET['url']);
    unset($_GET['url'][0]);
    $_GET['url'] = implode("/", $_GET['url']);

    if(empty($_GET['url']))
        $_GET['url'] = $_GET['app'];
}
else
    $_GET['app'] = DEFAULT_APP;

define("SRC_PHP", "src/php/");
define("SRC_JS", "src/js/");

define("PLUGINS", "plugins/");

define("APP", "app/");

define("CONTROLLERS", APP . $_GET['app'] . "/" . "controllers/");
define("MODELS", APP . $_GET['app'] . "/" . "models/");

define("VIEWS", "public/views/");
define("STYLES", WEB_SERVER . APP . $_GET['app'] . "/" . VIEWS . "assets/css/");
define("IMAGES", WEB_SERVER . APP . $_GET['app'] . "/" . VIEWS . "assets/img/");
define("SCRIPTS", WEB_SERVER . APP . $_GET['app'] . "/" . VIEWS . "assets/scripts/");
define("TEMPLATES", APP . $_GET['app'] . "/" . VIEWS . "templates/");
define("LIB", "lib/");

define("DATA", APP . $_GET['app'] . "/data/");
define("SQL", DATA . "sql/");
define("LANG", DATA . "language/");
define("STORAGE", DATA . "storage/");

define("LOGS", "logs/");

# HTTP status codes
define("HTTP_STATUS_OK", 200);
define("HTTP_STATUS_NOT_FOUND", 404);
define("HTTP_STATUS_MOVED_PERNAMENTLY", 301);
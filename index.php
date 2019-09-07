<?php
session_start();

# Include config and defaults
require_once("config/config.php"); // Load mattLib config file
require_once(APP . $_GET['app'] . "/config/config.php"); // Load app config file

require_once(LIB . "vendor/autoload.php");

require_once("includes/defaults.inc.php"); // Includes defaults
require_once("includes/env.inc.php"); // Includes env variables

# Autoload all library source files
spl_autoload_register(function($className) {
    if(file_exists(SRC_PHP . "$className.php"))
       require_once(SRC_PHP . "$className.php");
    if(file_exists(PLUGINS . "$className.php"))
       require_once(PLUGINS . "$className.php");
});

# Finally, include app.php, where the entire application is put together
require_once(APP . $_GET['app'] . "/app.php");
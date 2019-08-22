<?php
session_start();

# Include config and defaults
require_once("config/config.php"); // Load mattLib config file
require_once(APP . $_GET['app'] . "/config/config.php"); // Load app config file

require_once("includes/defaults.inc.php"); // Includes

# Autoload all library source files
function __autoload($className) {
    if(file_exists(SRC_PHP . "$className.php"))
       require_once(SRC_PHP . "$className.php");
    if(file_exists(PLUGINS . "$className.php"))
       require_once(PLUGINS . "$className.php");
}

# Include JS scripts if request is not ajax, can be spoofed, but isn't dangerous
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
    include 'includes/scripts.inc.php';

# Finally, include app.php, where the entire application is put together
require_once(APP . $_GET['app'] . "/app.php");

# Modify all links
if(MODIFY_LINKS && $_GET['url'] != "files" && !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
    echo "<script> ml.modifyHref() </script>";
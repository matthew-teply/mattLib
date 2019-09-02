<?php
require_once '../config/config.php';
require_once 'config/terminal.config.php';

require_once 'src/utils.php';
require_once 'src/route.php';
require_once 'src/app.php';

/*  
    --- Args ---
    1. Action
    2. Type
    3. App name
    4. Route
    5. Flags

    ex 1: create app   test
    ex 2: create route test blog/list
    ex 3: create route test common/home -hf
*/ 

$arg_action = $argv[1];
$arg_type   = $argv[2];
$arg_app    = $argv[3];
$arg_route  = $argv[4];

// * Assign flags
$flags = [];

for($i = 5; $i < sizeof($argv); $i++) {
    array_push($flags, $argv[$i]);
}
// ! Assign flags

if(!empty($arg_action)) {
    if(empty($arg_app)) {
        exit(ToolUtils::textColor("App has to be specified!\n", FORE_WHITE, BACK_RED));
    }

    switch($arg_action) {
        // * CREATE
        case "create":
            if(!empty($arg_type)) {
                switch($arg_type) {
                    case "app":
                        ToolApp::create($arg_app, $flags);
                    break;
        
                    case "route":
                        ToolRoute::create($arg_app, $arg_route, $flags);
                    break;
        
                    default:
                        echo ToolUtils::textColor("Unercognised type!\n", FORE_WHITE, BACK_RED);
                    break;
                }
            }

            else {
                echo ToolUtils::textColor("Type has to be specified!\n", FORE_WHITE, BACK_RED);
            }
        break;
        // ! CREATE
    
        // * DELETE
        case "delete":
            if(!empty($arg_type)) {
                switch($arg_type) {
                    case "app":
                        ToolApp::delete($arg_app, $flags);
                    break;
        
                    case "route":
                        ToolRoute::delete($arg_app, $arg_route, $flags);
                    break;
        
                    default:
                        echo ToolUtils::textColor("Unercognised type!\n", FORE_WHITE, BACK_RED);
                    break;
                }
            }

            else {
                echo ToolUtils::textColor("Type has to be specified!\n", FORE_WHITE, BACK_RED);
            }
        break;
        // ! DELETE

        default:
            echo ToolUtils::textColor("Unercognised action!\n", FORE_WHITE, BACK_RED);
        break;
    }
}

else {
    echo ToolUtils::textColor("Action has to be specified!\n", FORE_WHITE, BACK_RED);
}
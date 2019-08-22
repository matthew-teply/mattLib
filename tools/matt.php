<?php
require_once '../config/config.php';

require_once 'src/utils.php';
require_once 'src/route.php';
require_once 'src/app.php';

/*  --- Args ---
    1. Action
    2. Type
    3. App name
    4. Route

    ex 1: create  app        test
    ex 2: create  route      test  blog/list
    ex 3: create  component  test  prompts/error
*/

switch($argv[1]) {
    // * CREATE
    case "create":
        switch($argv[2]) {
            case "app":
                $app = new ToolApp;

                $app->create($argv[3]);
            break;

            case "route":
                $route = new ToolRoute($argv[3]);

                $route->create($argv[4]);
            break;

            default:
                echo "Unercognised command!";
            break;
        }
    break;
    // ! CREATE

    // * DELETE
    case "delete":
        switch($argv[2]) {
            case "app":
                $app = new ToolApp;

                $app->delete($argv[3]);
            break;

            case "route":
                $route = new ToolRoute($argv[3]);

                if($argv[5] == "-dir")
                    $route->delete_dir($argv[4]);
                else
                    $route->delete($argv[4]);
            break;

            case "component":
                $comp = new ToolComponents($argv[3]);

                if($argv[5] == "-dir")
                    $comp->delete_dir($argv[4]);
                else
                    $comp->delete($argv[4]);
            break;

            default:
                echo "Unercognised command!";
            break;
        }
    break;
    // ! DELETE
}
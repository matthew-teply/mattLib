<?php

class ToolUtils {

    public static $app_path_controllers;
    public static $app_path_templates;

    public function __construct() {
        
    }

    public function textColor(string $str, string $fore_color = "", $back_color = "") {
        $color =  "\e[";
        $color .= $fore_color;

        if(!empty($fore_color) && !empty($back_color))
            $color .= ";";

        $color .= $back_color;

        return $color . "m" . $str . "\e[0m";
    }

    public function confirm(string $prompt) {
        system("stty -icanon");
        
        echo $prompt;
        
        if(strtolower(fread(STDIN, 1)) != "y")
            exit("\n");

        echo "\n";
    }

    public function getAppPaths($app_name) {
        self::$app_path_controllers = APP . $app_name . "/controllers/";
        self::$app_path_templates = APP . $app_name . "/public/views/templates/";
    }

    # Delete dir/file recursively
    public function rrmdir($src) {
        if(!is_dir($src))
            exit("Directory '$src' doesn't exist!");

        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    self::rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

}
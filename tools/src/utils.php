<?php

class ToolUtils {

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
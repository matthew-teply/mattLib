<?php

class ToolApp extends ToolUtils {

    public function create(string $name, array $flags) {
        if(is_dir("../" . APP . $name)) {
            echo self::textColor("App '$name' already exists!\n", FORE_WHITE, BACK_RED);
            return;
        }

        # Array of all the default dirs of every app
        $dirs = [
            [
                "name" => "config"
            ],
            [
                "name" => "controllers"
            ],
            [
                "name" => "data",
                "subs" => [
                    "language",
                    "storage",
                    "sql"
                ]
            ],
            [
                "name" => "models"
            ],
            [
                "name" => "public",
                "subs" => [
                    "views",
                    "views/assets",
                    "views/assets/css",
                    "views/assets/scripts",
                    "views/assets/img",
                    "views/templates"
                ]
            ]
        ];

        # Storing original umask in a variable
        $o_umask = umask(0);

        # Create app's directory
        mkdir("../" . APP . $name, 0777, true);

        foreach($dirs as $dir) {
            # Create root dir
            mkdir("../" . APP . "$name/" . $dir['name'], 0777, true);

            # Create subdirs, if defined
            if(isset($dir['subs'])) {
                foreach($dir['subs'] as $sub) {
                    mkdir("../" . APP . "$name/" . $dir['name'] . "/" . $sub, 0777, true);
                }
            }
        }

        # Setting umask to it's original value
        umask($o_umask);

        # Create app's config file
        $config_code = <<<CODE
<?php
# Defaults
define("DEFAULT_HOMEPAGE", "home");
define("DEFAULT_LANGUAGE", "en");

# Files
define("MAX_FILESIZE", 1000000); // 1 MB

# Errors
define('ERR_NOT_FOUND', "404, page not found!");
define('ERR_FORBIDEN', "Permission denied!");

# Error redirects, leave empty if you just want to display a message
define('ERR_NOT_FOUND_REDIR', "");
define('ERR_FORBIDEN_REDIR', "");
CODE;

        $app_code = <<<CODE
<?php

App::get("home", function(\$req, \$res) {
    \$res->send("Homepage for $name");
});

App::init();
CODE;

        $db_env_code = <<<CODE
DB_HOST=localhost
DB_NAME=
DB_USER=root
DB_PASS=
CODE;

        # Create config.php and app.php
        $config_handle = fopen("../" . APP . "$name/config/config.php", "w");
        $db_env_handle = fopen("../" . APP . "$name/config/db.env", "w");
        $app_handle    = fopen("../" . APP . "$name/app.php", "w");

        # Write code into config.php and app.php
        fwrite($config_handle, $config_code);
        fwrite($db_env_handle, $db_env_code);
        fwrite($app_handle, $app_code);

        # Apply 0777 permissions
        chmod("../" . APP . "$name/config/config.php", 0777); 
        chmod("../" . APP . "$name/config/db.env", 0777); 
        chmod("../" . APP . "$name/app.php", 0777); 

        echo self::textColor("App '$name' has been created!\n", FORE_BLACK, BACK_GREEN);
        return;
    }

    public function delete(string $name, array $flags) {
        ToolUtils::confirm(ToolUtils::textColor("Are you sure you want to delete app '$name' (Y/N)", FORE_CYAN) . " : ");

        self::rrmdir("../" . APP . $name);

        echo self::textColor("App '$name' has been deleted!\n", FORE_BLACK, BACK_GREEN);
        return;
    }

}
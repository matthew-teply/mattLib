<?php

class ToolApp extends ToolUtils {

    public function create($name) {
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
                    "views/components",
                    "views/components/css",
                    "views/components/scripts",
                    "views/components/templates",
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

# Database
define("DB_HOST", "localhost");
define("DB_NAME", "");
define("DB_USER", "root");
define("DB_PASS", "");

# Errors
define('ERR_NOT_FOUND', "404, page not found!");
define('ERR_FORBIDEN', "Permission denied!");

# Error redirects, leave empty if you just want to display a message
define('ERR_NOT_FOUND_REDIR', "");
define('ERR_FORBIDEN_REDIR', "");

# Link
define("MODIFY_LINKS", true);
CODE;

        $app_code = <<<CODE
<?php

\$app = new App;

\$app->get("home", function(\$req, \$res) {
    \$res->send("Homepage for $name");
});

\$app->init();
CODE;

        # Create config.php and app.php
        $config_handle = fopen("../" . APP . "$name/config/config.php", "w");
        $app_handle    = fopen("../" . APP . "$name/app.php", "w");

        # Write code into config.php and app.php
        fwrite($config_handle, $config_code);
        fwrite($app_handle, $app_code);

        # Apply 0777 permissions
        chmod("../" . APP . "$name/config/config.php", 0777); 
        chmod("../" . APP . "$name/app.php", 0777); 

        exit("App '$name' has been created!\n");
    }

    public function delete($name) {
        $this->rrmdir("../" . APP . $name);

        exit("App '$name' has been deleted!\n");
    }

}
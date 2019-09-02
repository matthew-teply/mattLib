<?php

class ToolRoute extends ToolUtils {

    public function create(string $app, string $path, array $flags) {
        if(!is_dir("../" . APP . $app)) {
            echo self::textColor("App '$app' doesn't exist!\n", FORE_WHITE, BACK_RED);
            return;
        }

        self::getAppPaths($app);

        $controller_code = "";
        $view_code       = "";

        # Get directory name
        $dir = explode("/", $path);
        array_splice($dir, sizeof($dir) - 1);
        $dir = implode("/", $dir);

        # Storing original umask in a variable
        $o_umask = umask(0);

        # Create directory, if it doesn't exist
        if(!file_exists("../" . self::$app_path_controllers . $dir))
            mkdir("../" . self::$app_path_controllers . $dir, 0777, true);
        # Create directory, if it doesn't exist
        if(!file_exists("../" . self::$app_path_templates . $dir))
            mkdir("../" . self::$app_path_templates . $dir, 0777, true);

        # Setting umask to it's original value
        umask($o_umask);

        $path_controller = "../" . self::$app_path_controllers . "$path.controller.php";
        $path_view       = "../" . self::$app_path_templates . "$path.view.php";

        $handler_controller = fopen($path_controller, "w+") or die("Controller file could not be created!");
        $handler_view       = fopen($path_view, "w+") or die("View file could not be created!");

        $path_unexplode = $path;
        $path           = explode("/", $path);

        # Capitalize path
        foreach($path as $key => $i) {
            $path[$key] = ucfirst($i);
        }

        $path = implode("", $path);

        # If header and footer are requested through a flag
        if(in_array("-hf", $flags)) {
            $controller_code = <<<CODE
<?php

class Controller$path extends Controller {
    
    public function index() {
        \$data['header'] = \$this->load->view("partials/header");  
        \$data['footer'] = \$this->load->view("partials/footer");  
    
        \$this->display("$path_unexplode", \$data);
    }
    
}
CODE;
    
            $view_code = <<<CODE
<?= \$header ?>
    
    
    
<?= \$footer ?>
CODE;
        }

        else {
            $controller_code = <<<CODE
<?php
    
class Controller$path extends Controller {

    public function index() {
        \$this->display("$path_unexplode");
    }

}
CODE;
        }

        # Create controller
        fwrite(
            $handler_controller,
            $controller_code
        );

        # Create view
        fwrite(
            $handler_view,
            $view_code
        );

        # Give 0777 permission to controller and view files
        chmod($path_controller, 0777);
        chmod($path_view, 0777);

        fclose($handler_controller);
        fclose($handler_view);

        if(in_array("-hf", $flags))
            echo self::textColor("Route files for '$app/$path_unexplode' were created, with references to 'partials/header' and 'partials/footer'!\n", FORE_BLACK, BACK_GREEN);
        else
            echo self::textColor("Route files for '$app/$path_unexplode' were created!\n", FORE_BLACK, BACK_GREEN);
    
        return;
    }

    public function delete(string $app, string $path, array $flags) {
        self::getAppPaths($app);

        if(in_array("-dir", $flags)) {
            ToolUtils::confirm(ToolUtils::textColor("Are you sure you want to delete route directory '$app/$path' (Y/N)", FORE_CYAN) . " : ");

            self::rrmdir("../" . self::$app_path_controllers . "$path");
            self::rrmdir("../" . self::$app_path_templates . "$path");

            echo self::textColor("Route directory '$app/$path' was deleted!\n", FORE_BLACK, BACK_GREEN);
            return;
        }
        
        else {
            ToolUtils::confirm(ToolUtils::textColor("Are you sure you want to delete route files for '$app/$path' (Y/N)", FORE_CYAN) . " : ");

            unlink("../" . self::$app_path_controllers . "$path.controller.php");
            unlink("../" . self::$app_path_templates . "$path.view.php");

            echo self::textColor("Route files for '$app/$path' were deleted!\n", FORE_BLACK, BACK_GREEN);
            return;
        }
    }

}
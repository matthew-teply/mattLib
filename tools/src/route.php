<?php

class ToolRoute extends ToolUtils {

    public $app_path_controllers;
    public $app_path_templates;

    public function __construct($app_name) {
        $this->app_path_controllers = APP . $app_name . "/controllers/";
        $this->app_path_templates = APP . $app_name . "/public/views/templates/";
    }

    public function create(string $path) {
        # Get directory name
        $dir = explode("/", $path);
        array_splice($dir, sizeof($dir) - 1);
        $dir = implode("/", $dir);

        # Storing original umask in a variable
        $o_umask = umask(0);

        # Create directory, if it doesn't exist
        if(!file_exists("../" . $this->app_path_controllers . $dir))
            mkdir("../" . $this->app_path_controllers . $dir, 0777, true);
        # Create directory, if it doesn't exist
        if(!file_exists("../" . $this->app_path_templates . $dir))
            mkdir("../" . $this->app_path_templates . $dir, 0777, true);

        # Setting umask to it's original value
        umask($o_umask);

        $path_controller = "../" . $this->app_path_controllers . "$path.controller.php";
        $path_view = "../" . $this->app_path_templates . "$path.view.php";

        $handler_controller = fopen($path_controller, "w+") or die("Controller file could not be created!");
        $handler_view = fopen($path_view, "w+") or die("View file could not be created!");

        $path_unexplode = $path;
        $path = explode("/", $path);

        # Capitalize path
        foreach($path as $key => $i) {
            $path[$key] = ucfirst($i);
        }

        $path = implode("", $path);

        # Create controller
        fwrite(
            $handler_controller,
<<<CODE
<?php

class Controller$path extends Controller {

    public function index() {
        \$data['header'] = \$this->load->view("partials/header");  
        \$data['footer'] = \$this->load->view("partials/footer");  

        \$this->display("$path_unexplode", \$data);
    }

}
CODE
        );

        # Create view
        fwrite(
            $handler_view,
<<<CODE
<?= \$header ?>



<?= \$footer ?>
CODE
        );

        # Give 0777 permission to controller and view files
        chmod($path_controller, 0777);
        chmod($path_view, 0777);

        fclose($handler_controller);
        fclose($handler_view);
    }

    public function delete(string $path) {
        unlink("../" . $this->app_path_controllers . "$path.controller.php");
        unlink("../" . $this->app_path_templates . "$path.view.php");
    }
    
    public function delete_dir(string $path) {
        $this->rrmdir("../" . $this->app_path_controllers . "$path");
        $this->rrmdir("../" . $this->app_path_templates . "$path");
    }

}
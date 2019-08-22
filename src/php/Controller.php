<?php

class Controller {

    public function __construct() {
        $this->load     = $this;
        $this->language = new Language;
        $this->fs       = new FileSystem;
        $this->url      = new Url;
        $this->logs    = new Logs;
    }

    public static function display(string $viewName, array $viewData = []) {
        if(isset($_POST['command_subm']))
            return;

        foreach(array_keys($viewData) as $key) {
            $$key = $viewData[$key];
        }

        $viewPath = TEMPLATES . "$viewName.view.php";

        if(file_exists($viewPath)) {  
            if(!empty($GLOBALS['styles']))
                foreach($GLOBALS['styles'] as $key => $path) {
                    # Include style
                    echo "<link rel='stylesheet' href='$path'>";
                    # Unload style
                    unset($GLOBALS['styles'][$key]);
                }

            include $viewPath;

            if(!empty($GLOBALS['scripts']))
                foreach($GLOBALS['scripts'] as $key => $path) {
                    # Include script
                    echo "<script src='$path'></script>";
                    # Unload script
                    unset($GLOBALS['scripts'][$key]);
                }
        }

        else {
            exit("View $viewName does not exist!<br>App: " . $_GET['app']);
        }
    }

    public function style($path) {
        array_push($GLOBALS['styles'], STYLES . "$path.css");
    }

    public function script($path) {
        array_push($GLOBALS['scripts'], SCRIPTS . "$path.js");
    }

    public function component($comp_path) {
        echo '<link rel="stylesheet" href="' . WEB_SERVER . COMPONENTS . "/css/$comp_path" . '.comp.css">';          
        
        ob_start();
            include COMPONENTS . "templates/$comp_path.comp.php";
        $template = ob_get_clean();
        
        echo '<script src="' . WEB_SERVER . COMPONENTS . "scripts/$comp_path" . '.comp.js"></script>';     
        echo '<script>index(`' . $template . '`)</script>';

        return true;
    }

    public static function controller($path) {
        require_once CONTROLLERS . "/$path.controller.php";

        $className = "Controller" . implode("", explode("/", $path));

        return new $className;
    }

    public static function model($path) {
        require_once MODELS . "/$path.model.php";

        $className = "Model" . implode("", explode("/", $path));

        return new $className;
    }

    public static function view($path) {
        ob_start();
            self::controller($path)->index();
            
            return ob_get_clean();
    }

    public function language($path) {
        $lang = [];

        if(file_exists(LANG . $_SESSION['language'] . "/" . $path . ".lang.php"))
            include LANG . $_SESSION['language'] . "/" . $path . ".lang.php";
        
        return $lang;
    }
}
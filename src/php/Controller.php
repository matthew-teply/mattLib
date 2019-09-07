<?php

class Controller {

    public function __construct() {
        $this->load     = $this;
        $this->language = new Language;
        $this->fs       = new FileSystem;
        $this->url      = new Url;
        $this->logs     = new Logs;
        $this->curl     = new cURL;
    }

    public static function display(string $viewName, array $viewData = []) {
        if(isset($_POST['command_subm']))
            return;

        foreach(array_keys($viewData) as $key) {
            $$key = $viewData[$key];
        }

        $viewPath = TEMPLATES . "$viewName.view.php";

        if(file_exists($viewPath)) {  
            # Include JS scripts if request is not ajax, can be spoofed, but isn't dangerous
            /*if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
                include 'includes/scripts.inc.php';*/

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

            # Modify all links
            /*if(MODIFY_LINKS && $_GET['url'] != "files" && !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
                echo "<script> ml.modifyHref() </script>";*/
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
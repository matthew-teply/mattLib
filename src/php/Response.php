<?php

class Response extends URL {

    public function controller(string $route) {
        if(file_exists(CONTROLLERS . $route . ".controller.php")) { # Load controller
            require_once CONTROLLERS . "/$route.controller.php";

            $className = "Controller" . implode("", explode("/", $route));

            return new $className;
        }

        else
            exit("Controller for " . CONTROLLERS . " $route doesn't exist!");
    }

    public static function model(string $path) {
        require_once MODELS . "/$path.model.php";

        $className = "Model" . implode("", explode("/", $path));

        return new $className;
    }

    public function send($msg) {
        if(!is_array($msg))
            echo $msg;
        else
            print_r($msg);
    }

    public function redirect(string $to) {
        header("Location:" . $this->link($to));
    }

}
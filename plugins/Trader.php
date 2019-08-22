<?php

class Trader {

    public $app_path;
    public $component_path;
    public $controller_path;
    public $model_path;
    public $script_path;
    public $style_path;

    public function __construct($app) {
        $this->app_path = APP . "$app/";

        $this->component_path = $this->app_path . "public/views/components/";
        $this->controller_path = $this->app_path . "controllers/";
        $this->model_path = $this->app_path . "models/";
        $this->script_path = $this->app_path . "public/views/assets/scripts";
        $this->style_path = $this->app_path . "public/views/assets/css";
    }

    public function model($path) {
        require_once $this->model_path . "/$path.model.php";

        $className = "Model" . implode("", explode("/", $path));

        return new $className;
    }

    public function route($path, $post = []) {
        $curl = curl_init(getenv("HTTP_HOST") . WEB_SERVER . $path);

        if(!empty($post)) {
            curl_setopt_array($curl, [
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post
            ]);
        }

        return curl_exec($curl);
    }

}
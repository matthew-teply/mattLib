<?php

class Request {

    public function __construct($params) {
        $this->params = $params;
    }
    
    public function ajax($model, $method) {
        if(isset($_POST['ajax_subm'])) {
            if($_POST['method_name'] == $method) {
                $model->{$method}(...$_POST['method_data']);
                exit();
            }
        }
    }

}
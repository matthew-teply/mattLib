<?php

class Registry {

    protected $reg_file;
    protected $reg_vals;

    public function __construct($reg_file) {
        $this->reg_file = $reg_file;
        $this->reg_vals = json_decode(file_get_contents(REGISTRY . $reg_file . ".json"), true)[0];
    }

    public function set($reg_name, $reg_val) {
        $this->reg_vals[$reg_name] = $reg_val;
    }

    public function get($reg_name) {
        return $this->reg_vals[$reg_name];
    }

}
<?php

class ControllerErrors404 extends Controller {

    public function index() {
        $this->display("errors/404");
    }

}
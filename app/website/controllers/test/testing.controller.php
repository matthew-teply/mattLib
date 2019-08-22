<?php

class ControllerTestTesting extends Controller {

    public function index($msg) {
        $data['msg'] = $msg;

        $this->display("test/testing", $data);
    }

}
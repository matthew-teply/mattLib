<?php
    
class ControllerTestComponent extends Controller {

    public function index() {
        $this->load->script("component");
        $this->load->style("component");

        $this->display("test/component");
    }

}
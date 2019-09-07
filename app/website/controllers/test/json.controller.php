<?php
    
class ControllerTestJson extends Controller {

    public function index() {
        $json = json_encode([
            "name"       => "Matyáš Teplý",
            "age"        => 20,
            "birth_date" => "1.8. 1999"
        ]);

        echo $json;
    }

}
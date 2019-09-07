<?php
    
class ControllerTestCurl extends Controller {

    public function index() {
        $comments = $this->curl->getJSON("https://jsonplaceholder.typicode.com/posts/1/comments");

        foreach($comments as $comment) {
            echo "<hr>";
            echo "<h2>" . $comment["name"] . "</h2>";
            echo "<i>" . $comment["email"] . "</i>";
            echo "<p>" . $comment["body"] . "</p>";
        }

        $component = $this->curl->get("http://localhost/mattLib2/test/component");

        echo $component;

        $this->display("test/curl");
    }

}
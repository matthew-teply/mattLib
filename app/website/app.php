<?php

App::get("home", function($req, $res) {
    $res->send("Homepage for website");
});

App::init();
<?php

App::get("home", function($req, $res) {
    $res->send("Homepage for website");
});

App::get("test/curl", function($req, $res) {
    $res->controller("test/curl")->index();
});

App::get("users/matthew", "test/json@index");
App::get("test/component", "test/component@index");

App::init();
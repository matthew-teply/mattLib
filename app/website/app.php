<?php

App::get("home", function($req, $res) {
    $res->send("Homepage for website");
});

App::get("test/{msg}", "test/testing@index(:msg)");

App::get("404", "errors/404@index()");

App::init();
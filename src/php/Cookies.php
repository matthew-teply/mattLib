<?php

class Cookies {

    public function set(string $name, $value, int $duration) {
        if(setcookie($name, $value, time() + $duration, "/")) {
            return true;
        }

        return false;
    }

    public function get(string $name) {
        return $_COOKIE[$name];
    }

    public function destroy(string $name) {
        if(setcookie($name, "", time() - 3600))
            return true;

        return false;
    }
}
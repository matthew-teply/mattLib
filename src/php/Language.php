<?php

class Language {

    public function get($key) {
        return $GLOBALS['language'][$key];
    }

}
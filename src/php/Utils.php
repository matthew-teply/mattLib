<?php

class Utils {

    public function splitAndConnect($delimiter, $glue, $string) {
        return implode($glue, explode($delimiter, $string));
    }

}
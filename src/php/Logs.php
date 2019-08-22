<?php

class Logs {

    public function set($msg, $log) {
        $handle = fopen(LOGS . "$log.log", "a");
        fwrite($handle, $msg . "\n");
        fclose($handle);
    }

    public function get($log) {
        return file_get_contents(LOGS . "$log.log");
    }
}
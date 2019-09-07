<?php

class cURL {

    public function get(string $url, array $options = []) {
        $curl = curl_init($url);

        curl_setopt_array($curl, $options);
    
        ob_start();
            curl_exec($curl);
        return ob_get_clean();
    }

    public function getJSON(string $url) {
        return json_decode($this->get($url), true);
    }

}
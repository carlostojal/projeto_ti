<?php

    // this class is to be JSON serialized as the API response for all requests
    class APIResponse {

        public $message = "OK";
        public $status = 200;
        public $data = null;

        function send() {
            header("Content-Type:application/json");
            $json = json_encode($this);
            echo $json;
            http_response_code($this->status);
            exit;
        }

    }
?>
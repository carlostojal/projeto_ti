<?php

    // this is the superclass of all the JSON serializable classes
    class APISerializable {

        function get_json() {

            $json = json_encode($this);
            return $json;

        }

        function load($path) {

            $json = file_get_contents($path);
            $this->parse_json($json);
        }

        function store($path) {

            $json = $this->get_json();
            file_put_contents($path, $json);

        }

    }
?>
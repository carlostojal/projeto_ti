<?php

    require_once("./classes/serializable.php");

    class Sensor extends APISerializable {

        public $name = null;
        public $display_name = null;
        public $value = null;
        public $time = null;

    }
?>
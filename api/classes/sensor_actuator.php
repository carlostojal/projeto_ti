<?php

    require_once("./classes/serializable.php");

    class SensorActuator extends APISerializable {

        public $id = null;
        public $name = null;
        public $display_name = null;
        public $value = null;
        public $time = null;
        public $type = null; // sensor/actuator
        public $automatic = null;
    }
?>
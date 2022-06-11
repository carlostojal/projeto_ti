<?php

    require_once("./classes/serializable.php");

    // this class represents a history event (log entry)
    class HistoryEvent extends APISerializable {

        public $time = null;
        public $sensor_name = "Unknown";
        public $value = -1;

        function __construct() {
            $this->time = date("Y-m-d H:i:s");
        }

    }

?>
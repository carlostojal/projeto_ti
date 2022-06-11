<?php

    require_once("./constants.php");
    require_once("./classes/serializable.php");
    require_once("./classes/history_event.php");

    // this class represents and manages the history (log file)
    class History extends APISerializable {

        public $events = array();

        // load the log entries into the array
        function load($path) {

            $file = fopen($path, "r");
    
            while(!feof($file)) {
    
                $line = fgets($file);
                if($line == "" || $line == "\n") {
                    continue;
                }
                $line = trim($line);
                $fields = explode(";", $line);
                
                // create event object
                $history_event = new HistoryEvent();
                $history_event->time = $fields[0];
                $history_event->sensor_name = $fields[1];
                $history_event->value = $fields[2];
    
                array_push($this->events, $history_event);
    
            }
    
            fclose($file);
            
        }
    
        // save the log entries to the file
        function save($path) {
    
            $file = fopen($path, "w");
    
            foreach($this->events as $history_event) {
    
                $line = $history_event->time . ";" . $history_event->sensor_name . ";" . $history_event->value . PHP_EOL;
                fwrite($file, $line);
    
            }
    
            fclose($file);
        }
    
        // add a new event to the log
        function add($event) {
    
            array_push($this->events, $event);
    
        }

        // get the events to a specific sensor
        function get($sensor_name) {
            
            $result = array();
            
            foreach($this->events as $event) {
                if($event->sensor_name == $sensor_name) {
                    array_push($result, $event);
                }
            }
            
            return $result;
        }

    }

?>
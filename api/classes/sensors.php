<?php

    require_once("./classes/serializable.php");
    require_once("./classes/sensor_actuator.php");

    // this class represents all the sensors and last known values
    class Sensors {

        public $sensors = [];

        function parse_json($json) {

            $sensors = json_decode($json);

            foreach($sensors as $sensor) {
                $sensor1 = new Sensor();
                $sensor1->name = $sensor->name;
                $sensor1->display_name = $sensor->display_name;
                $sensor1->value = $sensor->value;
                $sensor1->time = $sensor->time;
                array_push($this->sensors, $sensor1);
            }

        }

        function load() {

            $db = new SQLite3(DB_PATH);

            $res = $db->query("SELECT * FROM SensorsActuators");

            while($row = $res->fetchArray()) {
                $sensor = new SensorActuator();
                $sensor->id = $row['id'];
                $sensor->name = $row['name'];
                $sensor->display_name = $row['display_name'];
                $sensor->value = $row['value'];
                $sensor->time = $row['time'];
                $sensor->type = $row['type'];
                $sensor->automatic = $row['automatic'];
                array_push($this->sensors, $sensor);
            }

            $db->close();
        }

        function getValue($name) {

            foreach($this->sensors as $sensor) {

                if($sensor->name == $name) {
                    return $sensor;
                }

            }
                
        }

        function setValue($name, $value) {

            foreach($this->sensors as &$sensor) {

                if($sensor->name == $name) {

                    $sensor->value = $value;
                    $sensor->time = time();

                    $db = new SQLite3(DB_PATH);

                    $stmt = $db->prepare("UPDATE SensorsActuators SET value=:value, time=:time WHERE name=:name");

                    $stmt->bindValue(":value", $sensor->value, SQLITE3_TEXT);
                    $stmt->bindValue(":time", $sensor->time, SQLITE3_INTEGER);
                    $stmt->bindValue(":name", $sensor->name, SQLITE3_TEXT);

                    $res = $stmt->execute();

                    return;
                }

            }

        }

        function add($sensor) {

            array_push($this->sensors, $sensor);

        }
    }
?>
<?php

    require_once("./classes/serializable.php");
    require_once("./constants.php");

    // this class represents the AC state
    class AC {

        public $temp = null; // temperature set
        public $fan_speed = 0.0; // AC fan RPM

        function load() {

            $db = new SQLite3(DB_PATH);

            $res = $db->query("SELECT * FROM SensorsActuators WHERE name='ac_temp' OR name='ac_fan_speed'");

            while($row = $res->fetchArray()) {

                switch($row['name']) {

                    case 'ac_temp':
                        $this->temp = (float) $row['value'];
                        break;

                    case 'ac_fan_speed':
                        $this->fan_speed = (float) $row['value'];
                        break;
                }
            }

            $db->close();
        }

        function store() {

            $db = new SQLite3(DB_PATH);

            $stmt = $db->prepare("UPDATE SensorsActuators SET value=:value WHERE name=:name");

            $stmt->bindValue(":value", $this->temp);
            $stmt->bindValue(":name", "ac_temp");
            $stmt->execute();

            $stmt->bindValue(":value", $this->fan_speed);
            $stmt->bindValue(":name", "ac_fan_speed");
            $stmt->execute();

        }

    }

?>
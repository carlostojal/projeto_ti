<?php

    require_once("./classes/headlights.php");
    require_once("./classes/ac.php");

    class ConfigWrapper {

        public $headlights = null;
        public $ac = null;
        public $windshield_wiper = null;

        function __construct() {    
            $this->headlights = new Headlights();
            $this->ac = new AC();
        }

        function load($username) {

            $db = new SQLite3(DB_PATH);

            $stmt = $db->prepare("SELECT * FROM UserConfigs WHERE user_id=(SELECT id FROM Users WHERE username=:username)");
            $stmt->bindValue(":username", $username);

            $res = $stmt->execute();

            $row = $res->fetchArray();

            if($row) {
                $this->headlights->high_beam = $row['high_beam'];
                $this->headlights->low_beam = $row['low_beam'];
                $this->ac->temp = $row['ac_temp'];
                $this->ac->fan_speed = $row['ac_speed'];
                $this->windshield_wiper = $row['windshield_wiper_speed'];
            }
            
        }

    }
?>
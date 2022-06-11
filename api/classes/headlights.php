<?php 

    require_once("./classes/serializable.php");
    require_once("./constants.php");

    // this class represents the headlights state
    class Headlights {

        public $high_beam = false;
        public $low_beam = false;

        function __construct() {
            $this->load();
        }

        function get_status($type) {

            switch($type) {

                case 'high_beam':
                    return $this->high_beam;
                    break;
                case 'low_beam':
                    return $this->low_beam;
                    break;
                default:
                    throw new Exception("INVALID_TYPE");
                    break;
            }
        }

        function flip_status($type) {

            switch($type) {

                case 'high_beam':
                    $this->high_beam = !$this->high_beam;
                    break;
                case 'low_beam':
                    $this->low_beam = !$this->low_beam;
                    break;
                default:
                    throw new Exception("INVALID_TYPE");
                    break;

            }

            $this->store();
        }

        function load() {
            
            $db = new SQLite3(DB_PATH);

            $res = $db->query("SELECT * FROM SensorsActuators WHERE type='actuator'");

            while($row = $res->fetchArray()) {
                switch($row['name']) {

                    case "high_beam":
                        $this->high_beam = (bool) $row['value'];
                        break;

                    case "low_beam":
                        $this->low_beam = (bool) $row['value'];
                        break;
                }
            }

            $db->close();
        }

        function store() {

            $db = new SQLite3(DB_PATH);

            $stmt = $db->prepare("UPDATE SensorsActuators SET value=:value WHERE name=:name");

            $stmt->bindValue(":value", $this->high_beam, SQLITE3_FLOAT);
            $stmt->bindValue(":name", "high_beam", SQLITE3_TEXT);
            $stmt->execute();

            $stmt->bindValue(":value", $this->low_beam, SQLITE3_FLOAT);
            $stmt->bindValue(":name", "low_beam", SQLITE3_TEXT);
            $stmt->execute();

        }

        function parse_json($json) {

            $obj = json_decode($json);
            $this->high_beam = $obj->high_beam;
            $this->low_beam = $obj->low_beam;

        }
    }
?>
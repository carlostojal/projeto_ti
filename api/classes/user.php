<?php

    require_once("./constants.php");
    require_once("./classes/serializable.php");
    require_once("./classes/config_wrapper.php");

    class User extends APISerializable{

        public $username = null;
        public $name = null;
        public $password_hash = null;
        public $configs = null;
        public $role = "user"; // user, admin, mechanic

        function __construct() {
            $this->configs = new ConfigWrapper();
        }

        function parse_json($json) {

            $obj = json_decode($json);
            $this->username = $obj->username;
            $this->password_hash = $obj->password_hash;
            if($obj->configs)
                $this->configs = $obj->configs;
            else
                $this->configs = new ConfigWrapper();
            $this->role = $obj->role;

        }

        function save() {

            $db = new SQLite3(DB_PATH);

            $stmt = $db->prepare("INSERT INTO Users(
                username,
                name,
                password_hash,
                role) VALUES (
                :username,
                :name,
                :password_hash,
                :role)");

            $stmt->bindValue(":username", $this->username, SQLITE3_TEXT);
            $stmt->bindValue(":name", $this->name, SQLITE3_TEXT);
            $stmt->bindValue(":password_hash", $this->password_hash, SQLITE3_TEXT);
            $stmt->bindValue(":role", $this->role, SQLITE3_TEXT);

            $res = $stmt->execute();

            return $res;

        }

        function get_token() {

            $token = base64_encode($this->username . ":" . $this->password_hash);
            return $token;
            
        }

    }

?>
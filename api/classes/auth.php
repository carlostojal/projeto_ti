<?php

    require_once("./constants.php");
    require_once("./classes/serializable.php");
    require_once("./classes/user.php");

    class Auth extends APISerializable {

        public $users = [];

        function __construct() {

            // $this->load_users();

        }

        function load_users() {

            $db = new SQLite3(DB_PATH);

            $res = $db->query("SELECT * FROM Users");

            $this->users = [];

            while($row = $res->fetchArray()) {
                $user = new User();
                $user->username = $row['username'];
                $user->password_hash = $row['password_hash'];
                $user->role = $row['role'];

                array_push($this->users, $user);
            }

            $db->close();

        }

        function store_users() {

            $json = json_encode($this->users);
            file_put_contents(USERS_PATH, $json);

        }

        function check_credentials($username, $password, $encrypted=false) {

            $db = new SQLite3(DB_PATH);

            $pass = $encrypted ? $password : md5($password);

            $stmt = $db->prepare("SELECT * FROM Users WHERE username=:username AND password_hash=:password");
            $stmt->bindValue(":username", $username, SQLITE3_TEXT);
            $stmt->bindValue(":password", $pass, SQLITE3_TEXT);

            $res = $stmt->execute();

            $row = $res->fetchArray();

            $db->close();

            if($row) {
                $user = new User();
                $user->username = $row['username'];
                $user->name = $row['name'];
                $user->password_hash = $row['password_hash'];
                $user->role = $row['role'];

                return $user;
            }
            

            return null;
        }

        function check_token($token) {

            $decoded = base64_decode($token);
            $credentials = explode(":", $decoded);

            $username = $credentials[0];
            $password = $credentials[1];

            $user = $this->check_credentials($username, $password, true);

            return $user;

        }
    }
?>
<?php

    require_once("./constants.php");
    require_once("./classes/response.php");
    require_once("./classes/auth.php");
    require_once("./classes/sensors.php");
    require_once("./classes/sensor.php");

    $response = new APIResponse();

    if(!isset($_COOKIE["token"])) {

        $response->status = 401;
        $response->message = "UNAUTHORIZED";
        $response->send();
    } else {

        $token = $_COOKIE["token"];
        $auth = new Auth();
        $user = $auth->check_token($token);

        if(!$user) {
            $response->status = 401;
            $response->message = "UNAUTHORIZED";
            $response->send();
        }

        // only the mechanic can install sensors
        if($user->role != "mechanic") {
            $response->status = 403;
            $response->message = "FORBIDDEN";
            $response->send();
        }

        if($_SERVER['REQUEST_METHOD'] == "POST") {

            if(isset($_POST['name']) && isset($_POST['display_name'])) {

                $sensors = new Sensors();
                $sensors->load();

                $new_sensor = new Sensor();
                $new_sensor->name = $_POST['name'];
                $new_sensor->display_name = $_POST['display_name'];

                $sensors->add($new_sensor);
                
                $sensors->store(SENSORS_PATH);
                
                $response->data = $sensor;
                $response->send();
    
            } else {

                $response->status = 400;
                $response->message = "BAD_REQUEST";
                $response->send();
    
            }
        }   
    }

    $response->send();
?>
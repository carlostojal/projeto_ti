<?php

    // get history (logs)
    
    require_once("./constants.php");
    require_once("./classes/history.php");
    require_once("./classes/response.php");
    require_once("./classes/auth.php");

    $response = new APIResponse();

    if(!isset($_COOKIE['token'])) {

        $response->status = 401;
        $response->message = "UNAUTHORIZED";
        $response->send();
    } else {

        $token = $_COOKIE['token'];
        $auth = new Auth();
        $user = $auth->check_token($token);

        if(!$user) {
            $response->status = 401;
            $response->message = "UNAUTHORIZED";
            $response->send();
        }

        // only the mechanic can view the sensor history
        if($user->role != "mechanic") {
            $response->status = 403;
            $response->message = "FORBIDDEN";
            $response->send();
        }

        if($_SERVER['REQUEST_METHOD'] == "GET") {

            $history = new History();
            $history->load(LOGS_PATH);

            // get for a specific sensor
            if(isset($_GET['sensor_name'])) {

                $response->data = $history->get($_GET['sensor_name']);

            } else {
                // get all sensors
                $response->data = $history->events;
            }
        

        } else {
            $response->status = 405;
            $response->message = "METHOD_NOT_ALLOWED";
        }
    }

    $response->send();
?>
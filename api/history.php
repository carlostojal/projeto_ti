<?php

    // get history (logs)
    
    require_once("./constants.php");
    require_once("./classes/history.php");
    require_once("./classes/response.php");
    require_once("./classes/auth.php");

    $response = new APIResponse();


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

    $response->send();
?>
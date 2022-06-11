<?php

    // get and update sensor values

    require_once("./constants.php");
    require_once("./classes/response.php");
    require_once("./classes/sensors.php");
    require_once("./classes/history.php");
    require_once("./classes/headlights.php");
    require_once("./classes/ac.php");
    require_once("./classes/history_event.php");
    require_once("./classes/auth.php");

    $response = new APIResponse();

    $sensors = new Sensors();
    $sensors->load();

    // update a sensor value
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['name']) && isset($_POST['value'])) {
            
            // set the sensor value in storage
            try {
                $sensors->setValue($_POST['name'], $_POST['value']);
            } catch(Exception $e) {
                $response->message = $e->getMessage();
                $response->status = 400;
            }

            // create a new history event
            $event = new HistoryEvent();
            $event->sensor_name = $_POST['name'];
            $event->value = $_POST['value'];

            // update the history (logs)
            $history = new History();
            $history->load(LOGS_PATH);
            $history->add($event);
            $history->save(LOGS_PATH);

            echo $_POST['value'];
            http_response_code(200);
            return;

        } else {
            $response->status = 400;
            $response->message = "SENSOR_NAME_OR_VALUE_NOT_PROVIDED";
        }

    } else if($_SERVER['REQUEST_METHOD'] == 'GET') {

        // a specific sensor was requested
        if(isset($_GET['name'])) {
                
            try {
                echo $sensors->getValue($_GET['name'])->value;
                http_response_code(200);

                return;
            } catch(Exception $e) {
                $response->message = $e->getMessage();
                $response->status = 400;
            }

        } else {
            // send all sensors data
            $response->data = $sensors->sensors;
        }
    } else {

        $response->status = 405;
        $response->message = "METHOD_NOT_ALLOWED";
    }    

    $response->send();
?>
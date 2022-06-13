<?php

    // set if an actuator will be controlled automatically or not

    require_once("./constants.php");
    require_once("./classes/response.php");
    require_once("./classes/sensors_actuators.php");

    $response = new APIResponse();

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(isset($_POST['name']) && isset($_POST['value'])) {

            if($_POST['value'] == 0 || $_POST['value'] == 1) {

                $sensors = new SensorsActuators();

                $sensors->setAutomatic($_POST['name'], $_POST['value']);

                $response->data = $sensors;

            } else {

                $response->status = 400;
                $response->message = "AUTOMATIC_NOT_BOOL";
            }

        } else {

            $response->status = 400;
            $response->message = "NAME_OR_VALUE_NOT_PROVIDED";
        }

    } else {
        $response->status = 405;
        $response->message = "METHOD_NOT_ALLOWED";
    }

    $response->send();
    
?>
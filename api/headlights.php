<?php

    // get and update headlights status

    require_once("./constants.php");
    require_once("./classes/response.php");
    require_once("./classes/headlights.php");
    require_once("./classes/auth.php");
    require_once("./classes/user.php");
    require_once("./classes/history.php");
    require_once("./classes/history_event.php");

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

        try {
        
            $headlights = new Headlights();
            $headlights->load(HEADLIGHTS_STATUS_PATH);

            $history = new History();
            $history->load(LOGS_PATH);

            $event = new HistoryEvent();
    
            // POST - update headlight status
            if($_SERVER['REQUEST_METHOD'] == "POST") {
    
                if(isset($_POST['type'])) {
    
                    try {
                        $headlights->flip_status($_POST['type']);
                        $headlights->store(HEADLIGHTS_STATUS_PATH);

                        if(!$user->configs)
                            $user->configs = new ConfigWrapper();
                        $user->configs->headlights = $headlights;
                        $auth->store_users();

                        $event->sensor_name = "headlights_".$_POST['type'];
                        $event->value = $headlights->get_status($_POST['type']);

                        $history->add($event);
                        $history->save(LOGS_PATH);

                        $response->data = $headlights;
                    } catch(Exception $e) {
                        $response->message = $e->getMessage();
                        $response->status = 400;
                    }
    
                } else {
    
                    $response->message = "PARAMETERS_MISSING";
                    $response->status = 400;
    
                }
    
            } else if($_SERVER['REQUEST_METHOD'] == "GET") {
    
                // GET - get headlight status
                $response->data = $headlights;
    
            } else {
    
                $response->message = "METHOD_NOT_ALLOWED";
                $response->status = 405;
            }
        } catch(Exception $e) {
            $response->message = $e->getMessage();
            $response->status = 500;
        }
    
        $response->send();

    }
?>
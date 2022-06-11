<?php

    // update temperatures and fan speed
    // get temperatures and fan speed

    require_once("./constants.php");
    require_once("./classes/response.php");
    require_once("./classes/ac.php");
    require_once("./classes/auth.php");
    require_once("./classes/user.php");
    require_once("./classes/config_wrapper.php");
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

            $ac = new AC();
            $ac->load();

            $history = new History();
            $history->load(LOGS_PATH);

            $event = new HistoryEvent();
    
            // set an AC param
            if($_SERVER['REQUEST_METHOD'] == "POST") {
    
                if(isset($_POST['param']) && isset($_POST['value'])) {
    
                    switch($_POST['param']) {
                        
                        case 'temp':
                            $ac->temp = (float) $_POST['value'];
                            $event->sensor_name = "temp";
                            $event->value = (float) $_POST['value'];
                            break;
                        case 'fan_speed':
                            $ac->fan_speed = (float) $_POST['value'];
                            $event->sensor_name = "fan_speed";
                            $event->value = (float) $_POST['value'];
                            break;
                        default:
                            $response->message = "BAD_REQUEST";
                            $response->status = 400;
                            break;
                    }
    
                    if(!$user->configs)
                        $user->configs = new ConfigWrapper();

                    $user->configs->ac = $ac;
                    $auth->store_users();

                    $response->data = $ac;
                    $ac->store();

                    $history->add($event);
                    $history->save(LOGS_PATH);

    
                } else {
    
                    $response->message = "PARAMETERS_MISSING";
                    $response->status = 400;
    
                }
            } else if($_SERVER['REQUEST_METHOD'] == "GET") {
    
                // get AC params
                $response->data = $ac;
    
            } else {
    
                $response->message = "METHOD_NOT_ALLOWED";
                $response->status = 405;
            }
    
        } catch(Exception $e) {
            $response->message = $e->getMessage();
            $response->status = 500;
        }
    }

    $response->send();
?>
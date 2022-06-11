<?php

    // returns all car configurations

    require_once("./constants.php");
    require_once("./classes/response.php");
    require_once("./classes/headlights.php");
    require_once("./classes/ac.php");
    require_once("./classes/config_wrapper.php");
    require_once("./classes/auth.php");

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

        // get config for all users
        if(isset($_GET["scope"]) && $_GET["scope"] == "all") {

            if($user->role == "admin" || $user->role == "mechanic") {

                $configs = array();
                foreach($auth->users as $user) {
                    if($user->configs)
                        array_push($configs, $user->configs);
                }
                $response->data = $configs;
                $response->send();

            } else {

                $response->status = 403;
                $response->message = "FORBIDDEN";
                $response->send();

            }

            $configs = new ConfigWrapper();
            $configs->load(CONFIG_PATH);

            $response->status = 200;
            $response->message = "OK";
            $response->data = $configs;
            $response->send();

        } else {

            // get config for current user

            $response->data = $user->configs;
            $response->send();

        }

    }
    try {

        $wrapper = new DataWrapper();

        // load headlights
        try {

            $headlights = new Headlights();
            $headlights->load(HEADLIGHTS_STATUS_PATH);

            $wrapper->headlights = $headlights;

        } catch(Exception $e) {
            $response->message = "ERROR_LOADING_HEADLIGHTS_DATA";
            $response->status = 500;
        }

        // load ac
        try {

            $ac = new AC();
            $ac->load(AC_STATUS_PATH);

            $wrapper->ac = $ac;

        } catch(Exception $e) {
            $response->message = "ERROR_LOADING_AC_DATA";
            $response->status = 500;
        }

        $response->data = $wrapper;

    } catch(Exception $e) {

        $response->message = $e->getMessage();
        $response->status = 500;
    }

    $response->send();

?>
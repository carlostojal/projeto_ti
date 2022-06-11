<?php

    require_once("./classes/response.php");
    require_once("./classes/auth.php");

    $response = new APIResponse();

    if(isset($_COOKIE['token'])) {

        $token = $_COOKIE['token'];

        $auth = new Auth();
        $user = $auth->check_token($token);

        $response->data = $user;

    } else {
        
        $response->status = 400;
        $response->message = "TOKEN_NOT_PROVIDED";

    }

    $response->send();
?>
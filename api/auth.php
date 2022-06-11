<?php

    require_once("./constants.php");
    require_once("./classes/response.php");
    require_once("./classes/auth.php");
    require_once("./classes/user.php");

    $response = new APIResponse();
    $auth = new Auth();

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(isset($_POST['username']) && isset($_POST['password'])) {
            
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $auth->check_credentials($username, $password, false);

            if($user) {

                $token = $user->get_token();

                setcookie("token", $token, time() + SESSION_DURATION, "/");

                $response->data = $token;

            } else {

                $response->status = 400;
                $response->message = "INVALID_CREDENTIALS";

            }

        } else {

            $response->status = 400;
            $response->message = "USERNAME_OR_PASSWORD_NOT_PROVIDED";
    
        }

    }

    $response->send();
?>
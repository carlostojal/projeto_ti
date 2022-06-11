<?php

    require_once("./constants.php");
    require_once("./classes/response.php");
    require_once("./classes/auth.php");
    require_once("./classes/user.php");

    $response = new APIResponse();

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(isset($_POST['username']) && isset($_POST['name']) && isset($_POST['password']) && isset($_POST['role'])) {

            $user = new User();
            $user->username = $_POST['username'];
            $user->name = $_POST['name'];
            $user->password_hash = md5($_POST['password']);
            $user->role = $_POST['role'];

            if(!$user->save()) {

                $response->status = 500;
                $response->message = "ERROR_SAVING_USER";
                $response->send();
            }
            
            $response->data = $user;
            $response->send();

        } else {

            $response->status = 400;
            $response->message = "BAD_REQUEST";
            $response->send();

        }
    }

    $response->send();
?>
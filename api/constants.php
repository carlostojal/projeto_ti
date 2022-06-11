<?php


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    
    // these are the constants used in the API
    
    define("LOGS_PATH", "./files/logs.txt");
    
    define("STORAGE_PATH", "./files/");

    define("HEADLIGHTS_STATUS_PATH", STORAGE_PATH."configs/headlights.json");
    define("AC_STATUS_PATH", STORAGE_PATH."configs/ac.json");

    define("SENSORS_PATH", STORAGE_PATH."sensors.json");

    define("USERS_PATH", STORAGE_PATH."users.json");

    define("SESSION_DURATION", 60 * 60 * 24 * 7); // 7 days

    define("DB_PATH", STORAGE_PATH."projeto.db");

?>
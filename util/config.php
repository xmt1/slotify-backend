<?php

    ob_start();

    $timezone = date_default_timezone_set("America/Phoenix");
    $con = '';
    try {
        $con = new PDO("mysql:host=localhost;dbname=slotify", 'root', '');
        // set the PDO error mode to exception
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    catch(PDOException $e)
        {
        echo "Connection failed: " . $e->getMessage();
        }

?>
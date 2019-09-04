<?php
    include('./util/cors.php');
    include('./util/config.php');
    include('./classes/Account.php');
    $account = new Account($con); // $con set in config.php
?>
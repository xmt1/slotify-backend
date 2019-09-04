<?php

    include('./main.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);
        $msg = [];

        $username = $data['username'];
        $password = $data['password'];

        // Login function
        $wasSuccessful = $account->login($username, $password);



        //Response message
        if (!$wasSuccessful) {
            $msg['success'] = false;
            $msg['errors'] = $account->getErrors();
        } else {
            $msg['success'] = true;
        }

        echo json_encode($msg);
    }
    
?>
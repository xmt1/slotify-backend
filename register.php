<?php
    include('./main.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include('./util/sanitize.php');
        
        // JSON data from front-end
        $data = json_decode(file_get_contents('php://input'), true);
        $msg = [];

        // POST data -- sanitize first
        $username = sanitizeFormUsername($data['username']);
        $firstName = sanitizeFormString($data['firstName']);
        $lastName = sanitizeFormString($data['lastName']);
        $email = sanitizeFormString($data['email']);
        $email2 = sanitizeFormString($data['email2']);
        $password = sanitizeFormPassword($data['password']);
        $password2 = sanitizeFormPassword($data['password2']);

        // Backend validation check
        $wasSuccessful = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);

        // Response message
        if (!$wasSuccessful) {
            $msg['success'] = false;
            $msg['errors'] = $account->getErrors();
        } else {
            $msg['success'] = true;
        }
        
        // Send back json to front-end
        echo json_encode($msg);

    }
    
?>
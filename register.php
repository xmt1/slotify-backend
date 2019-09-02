<?php
    include('./util/cors.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include('./classes/Account.php');
        include('./util/sanitize.php');
        $account = new Account();

        
        // JSON data from front-end
        $data = json_decode(file_get_contents('php://input'), true);

        // Post variables -- sanitize first
        $username = sanitizeFormUsername($data['username']);
        $firstName = sanitizeFormString($data['firstName']);
        $lastName = sanitizeFormString($data['lastName']);
        $email = sanitizeFormString($data['email']);
        $email2 = sanitizeFormString($data['email2']);
        $password = sanitizeFormPassword($data['password']);
        $password2 = sanitizeFormPassword($data['password2']);

        $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);

        

    } else {
        echo "Nothing was posted";
    }
    
?>
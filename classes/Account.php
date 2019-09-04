<?php
    class Account {
        private $con;
        private $errorArray;

        // Form error messages
        private $passwordsDoNotMatch = "Your passwords don't match";
        private $passwordNotAlphanumeric = "Your passwords can only contain numbers and letters";
        private $passwordCharacters = "Your username must be between 5 and 30 characters";
        private $emailInvalid = "Email is invalid";
        private $emailsDoNotMatch = "Your emails don't match";
        private $emailTaken = "This email already exists";
        private $lastNameCharacters = "Your Last Name must be between 2 and 25 characters";
        private $firstNameCharacters = "Your First Name must be between 2 and 25 characters";
        private $usernameCharacters = "Your username must be between 3 and 25 characters";
        private $usernameTaken = "This username already exists";
        
        private $loginFailed = "Your username or password was incorrect";
        

        public function __construct($con) {
            $this->errorArray = array();
            $this->con = $con;
        }

        public function login($un, $pw) {
            $encryptedPw = md5($pw);

            $usernameQuery = $this->con->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $usernameQuery->execute([$un, $encryptedPw]);
            $result = $usernameQuery->fetchAll();

            if (empty($result)) {
                $this->errorArray['loginFailed'] = $this->loginFailed;
                return false;
            } else {
                return true;
            }
        }

        public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
            $this->validateUsername($un);
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateEmails($em, $em2);
            $this->validatePasswords($pw, $pw2);

            if (empty($this->errorArray)) {
                // Insert into DB
                return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
            } else {
                return false;
            }

        }

        public function getErrors() {
            return $this->errorArray;
        }

        private function validateUsername($un) {
            if(strlen($un) > 25 || strlen($un) < 3) {
                $this->errorArray['usernameCharacters'] = $this->usernameCharacters;
                return;
            }

            $checkUsernameQuery = $this->con->prepare("SELECT username FROM users WHERE username = ?");
            $checkUsernameQuery->execute([$un]);
            $result = $checkUsernameQuery->fetchAll();

            if (!empty($result)) {
                $this->errorArray['usernameTaken'] = $this->usernameTaken;
                return;
            }
        }

        private function insertUserDetails($un, $fn, $ln, $em, $pw) {
            $encryptedPw = md5($pw);
            $profilePic = "assets/images/profile-pics/profile-pic.png";
            $date = date("Y-m-d");

            $statement = $this->con->prepare("INSERT INTO users VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            return $statement->execute(['', $un, $fn, $ln, $em, $encryptedPw, $date, $profilePic]);
        }

        private function validateFirstName($fn) {
            if(strlen($fn) > 25 || strlen($fn) < 2) {
                $this->errorArray['firstNameCharacters'] = $this->firstNameCharacters;
                return;
            }
        }

        private function validateLastName($ln) {
            if(strlen($ln) > 25 || strlen($ln) < 2) {
                $this->errorArray['lastNameCharacters'] = $this->lastNameCharacters;
                return;
            }
        }

        private function validateEmails($em, $em2) {
            if ($em != $em2) {
                $this->errorArray['emailsDoNotMatch'] = $this->emailsDoNotMatch;
                return;
            }

            if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                $this->errorArray['emailInvalid'] = $this->emailInvalid;
                return;
            }

            $checkEmailQuery = $this->con->prepare("SELECT email FROM users WHERE email = ?");
            $checkEmailQuery->execute([$em]);
            $result = $checkEmailQuery->fetchAll();
            
            if (!empty($result)) {
                $this->errorArray['emailTaken'] = $this->emailTaken;
                return;
            }
        }

        private function validatePasswords($pw, $pw2) {
            if ($pw != $pw2) {
                $this->errorArray['passwordsDoNotMatch'] = $this->passwordsDoNotMatch;
                return;
            }

            if (preg_match('/[^A-Za-z0-9]/', $pw)) {
                $this->errorArray['passwordNotAlphanumeric'] = $this->passwordNotAlphanumeric;
                return;
            }

            if(strlen($pw) > 30 || strlen($pw) < 5) {
                $this->errorArray['passwordCharacters'] = $this->passwordCharacters;
                return;
            }
        }
    }
?>
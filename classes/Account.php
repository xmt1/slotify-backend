<?php
    class Account {
        private $errorArray;

        private $passwordsDoNotMatch = "Your passwords don't match";
        private $passwordNotAlphanumeric = "Your passwords can only contain numbers and letters";
        private $passwordCharacters = "Your username must be between 5 and 30 characters";
        private $emailInvalid = "Email is invalid";
        private $emailsDoNotMatch = "Your emails don't match";
        private $lastNameCharacters = "Your Last Name must be between 2 and 25 characters";
        private $firstNameCharacters = "Your First Name must be between 2 and 25 characters";
        private $usernameCharacters = "Your username must be between 3 and 25 characters";
        

        public function __construct() {
            $this->errorArray = array();
        }

        public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
            $this->validateUsername($un);
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateEmails($em, $em2);
            $this->validatePasswords($pw, $pw2);

            if (empty($this->errorArray)) {
                // Insert into DB
                return true;
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

            //TODO: check if username exists
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

            //TODO: Check that email hasn't been used
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
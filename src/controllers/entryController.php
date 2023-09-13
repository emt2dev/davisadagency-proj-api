<?php

// entry controller
/*
*
* This controller is responsible for "registering" new users, "logging them"in via confirming email and password, finally, issues jwt.
* Note: the jwt is intercepted. If it is valid and exists, certain controllers are accessible. If the jwt is invalid, The token will redirect to login.
* Note: we are also creating refresh tokens that will be stored on client and will be sent from client instead of requiring end user to log in.
*
*/

class entryController {
    public string $descriptiveOne;
    public string $descriptiveTwo;

    public string $postedEmail;

    public $userRow = [];
    public $newUser = [];
    public $connection;

    public function __construct($connection) { $this->connection = $connection; }

    public function setDescriptiveOne(string $descriptiveOne) {
        $this->descriptiveOne = $descriptiveOne;
    }

    public function setDescriptiveTwo(string $descriptiveTwo) {
        $this->descriptiveTwo = $descriptiveTwo;
    }

    public function userExistsPreFlight(string $email) {
        $this->postedEmail = sanitizeFn($email);

        $query = "SELECT * FROM users WHERE email=$email";
        $querySend = mysqli_query($this->connection, $query);

        if ($querySend->num_rows === 0) {

            return false;
        } else {
            $row = mysqli_fetch_assoc($querySend);

            $this->userRow['id'] = $row['id'];
            $this->userRow['email'] = $row['email'];
            $this->userRow['name'] = $row['name'];
            $this->userRow['role'] = $row['role'];
            $this->userRow['password'] = $row['password'];
    
            return true;
        }
    }

    public function loginuserRole(string $pass) {
        if (password_verify($pass, $this->userRow['password'])) {

            $userFound = new userModel();
            $userFound->mapRow($this->userRow);

            return true;
        } else {

            unset($this->userRow);

            return false;
        }
    }

    public function registerUserRole(string $pass, string $email, string $name) {
        $query = "INSERT INTO users (email, roles, password_hash, name) VALUES (?,?,?,?)";

        $query = $this->connection->prepare($query);

            $query->bind_param("ssss", $a,$b,$c,$d);

                $a = $this->userRow['email'];
                $b = "user";
                $c = password_hash($this->userRow['password'], PASSWORD_DEFAULT);
                $d = $this->userRow['name'];

        $query->execute();

        return true;
    }
}
?>
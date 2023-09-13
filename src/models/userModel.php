<?php

class userModel {
    public string $email;
    public string $name;
    public string $role;
    public string $password;
    public string $preFlightResult;

    public function __construct() {}

    public function mapRow($row) {
        $this->email = $row['id'];
        $this->email = $row['email'];
        $this->name = $row['name'];
        $this->role = $row['role'];
        $this->password = $row['password'];
    }
}

?>
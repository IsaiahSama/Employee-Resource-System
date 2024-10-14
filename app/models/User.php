<?php 

class User {
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public Roles $auth_level;

    public function __construct($raw_user) {
        $this->id = $raw_user['user_id'];
        $this->username = $raw_user['username'];
        $this->email = $raw_user['email'];
        $this->password = $raw_user['password'];
        $this->auth_level = match ($raw_user['auth_level']) {
            Roles::EMPLOYEE->value => $this->auth_level = Roles::EMPLOYEE,
            Roles::MANAGER->value => $this->auth_level = Roles::MANAGER,
            Roles::ADMIN->value => $this->auth_level = Roles::ADMIN,
            default => $this->auth_level = Roles::GUEST
        };
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAuthLevel() {
        return $this->auth_level;
    }

    public function getHashedPassword() {
        return $this->password;
    }

}
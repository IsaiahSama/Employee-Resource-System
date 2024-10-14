<?php

require_once 'app/models/User.php';

class UserRepository {

    public static function createUser(string $username, string $email, string $password, int $role) {
        global $conn;

        // https://www.w3schools.com/php/php_mysql_prepared_statements.asp
        $sql = "INSERT INTO Users (username, email, password, auth_level) VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $password, $role);

        return $stmt->execute();
    }

    public static function getAllUsers() {
        global $conn;

        $sql = "SELECT * FROM Users";

        $query = $conn->query($sql);

        $raw_users = $query->fetch_all(MYSQLI_ASSOC);
        $users = [];
        
        foreach ($raw_users as $raw_user) {
            $users[] = new User($raw_user);
        }
        return $users;
    } 

    public static function getUserByEmail(string $email){
        global $conn;

        $sql = "SELECT * FROM Users WHERE email = '$email'";

        $query = $conn->query($sql);

        $raw_user = $query->fetch_assoc();

        if (!$raw_user) {
            return null;
        }

        return new User($raw_user);
    }

    public static function getUserById(int $id) {
        global $conn;

        $sql = "SELECT * FROM Users WHERE user_id = $id";

        $query = $conn->query($sql);

        $raw_user = $query->fetch_assoc();

        if (!$raw_user) {
            return null;
        }

        return new User($raw_user);
    }

    public static function deleteUserById(int $id) {
        global $conn;

        $sql = "DELETE FROM Users WHERE user_id = $id";

        return $conn->query($sql);
    }

    public static function queryUserByNotAuthLevel(int $auth_level) {
        global $conn;

        $sql = "SELECT * FROM Users WHERE auth_level != $auth_level";

        $query = $conn->query($sql);

        $raw_users = $query->fetch_all(MYSQLI_ASSOC);
        $users = [];
        
        foreach ($raw_users as $raw_user) {
            $users[] = new User($raw_user);
        }
        return $users;
    }
    
}
// 'comp3385'@'localhost'
// FrameworksPassword2024!
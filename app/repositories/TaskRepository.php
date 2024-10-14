<?php 

require_once 'app/models/Task.php';

class TaskRepository {
    public static function getAllTasks() {
        // https://www.w3schools.com/php/func_mysqli_fetch_all.asp
        global $conn;

        $sql = "SELECT * FROM Tasks";

        $query = $conn->query($sql);

        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = new Task($raw_task);
        }
        return $tasks;
    }

    public static function getTaskById(int $id) {
        global $conn;

        $sql = "SELECT * FROM Tasks WHERE task_id = $id";
        $query = $conn->query($sql);
        $raw_task = $query->fetch_assoc();

        if (!$raw_task) {
            return null;
        }

        return new Task($raw_task);
    }


    public static function getTasksByUserId(int $user_id) {
        global $conn;

        $sql = "SELECT * FROM Tasks WHERE assigned_to = $user_id";

        $query = $conn->query($sql);

        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = new Task($raw_task);
        }
        return $tasks;
    }

    public static function getTasksByOwnerId(int $owner_id) {
        global $conn;

        $sql = "SELECT * FROM Tasks WHERE created_by = $owner_id OR assigned_to = $owner_id ORDER BY task_id DESC";

        $query = $conn->query($sql);

        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = new Task($raw_task);
        }
        return $tasks;
    }

    public static function createTask(string $title, string $description, string $status, int $assigned_to, int $created_by, string $dueDate, string $comments = '') {
        global $conn;

        $sql = "INSERT INTO Tasks (title, description, status, assigned_to, created_by, due_date, comments) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmmt = $conn->prepare($sql);
        $stmmt->bind_param("sssiiss", $title, $description, $status, $assigned_to, $created_by, $dueDate, $comments);
        return $stmmt->execute();
    }

    public static function updateTask(int $id, string $title, string $description, string $status, int $assigned_to, string $dueDate, string $comments = '') {
        global $conn;

        $sql = "UPDATE Tasks SET title = ?, description = ?, status = ?, assigned_to = ?, due_date = ?, comments = ? WHERE task_id = $id";
        $stmmt = $conn->prepare($sql);
        $stmmt->bind_param("sssiss", $title, $description, $status, $assigned_to, $dueDate, $comments);
        return $stmmt->execute();
    }

    public static function getTasksByStatus(string $status, int $user_id, bool $is_admin = false) {
        global $conn;
        
        if ($is_admin) {
            $sql = "SELECT * FROM Tasks WHERE status = '$status'";
        } 
        else {
            $sql = "SELECT * FROM Tasks WHERE status RLIKE '$status\$' AND (assigned_to = $user_id OR created_by = $user_id)";
        }

        $query = $conn->query($sql);
        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = new Task($raw_task);
        }
        return $tasks;
    }
}
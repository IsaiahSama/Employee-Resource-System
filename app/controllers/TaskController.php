<?php 


class TaskController {

    public function index() {

        global $guard;

        $guard->hasAccess(Roles::MANAGER);

        $current_user = SessionManager::get(SessionValues::USER_INFO->value);
        $auth_level = $current_user['auth_level']->value;

        $taskable_users = [];

        if ($auth_level == Roles::ADMIN->value) {
            $taskable_users = UserRepository::getAllUsers();
        }
        else{
            $taskable_users = UserRepository::queryUserByNotAuthLevel(Roles::ADMIN->value);
        }

        header("HTTP/1.0 200 OK");
        render("create-task", ['title' => "Create Task", 'users' => $taskable_users]);
    }

    public function createTask() {
        global $guard;

        $guard->hasAccess(Roles::MANAGER);

        $current_user = SessionManager::get(SessionValues::USER_INFO->value);

        if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned']) && isset($_POST['dueDate']) ) {

            $title = $_POST['title'];
            $description = $_POST['description'];
            $assigned = $_POST['assigned'];
            $dueDate = $_POST['dueDate'];
            $status = Status::PENDING->value;

            $success = TaskRepository::createTask($title, $description, $status, $assigned, $current_user['id'], $dueDate);
            $success_route = '/dashboard/' . match($guard->getAuthLevel()) {
                Roles::ADMIN->value => 'admin',
                Roles::MANAGER->value => 'manager',
                default => 'manager'
            } . '?action=viewTasks';
            
            if ($success) {
                header("HTTP/1.0 301 OK");
                header("Location: $success_route");
                die();
            }

            else {
                header("HTTP/1.0 400 Bad Request");
                echo json_encode(["error" => "Bad Request"]);
                die();
            }
        }

        else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "Bad Request"]);
            die();
        }
    }

    public function edit() {

        global $guard;

        $guard->hasAccess(Roles::EMPLOYEE);

        $task = TaskRepository::getTaskById($_GET['id']);
        $current_user = SessionManager::get(SessionValues::USER_INFO->value);

        if (empty($task)) {
            header("HTTP/1.0 400 Bad Request");
            render('errors/404', ['title' => "Task Not Found"]);
            die();
        }

        $is_authorized = ($task->assigned_to == $current_user['id'] || $task->created_by == $current_user['id'] || $current_user['auth_level'] == Roles::ADMIN);

        if (!($is_authorized)) {
            header("HTTP/1.0 401 Unauthorized");
            render('errors/401', ['title' => "Access Denied"]);
            die();
        }

        $auth_level = $current_user['auth_level']->value;

        $taskable_users = [];

        if ($auth_level == Roles::ADMIN->value) {
            $taskable_users = UserRepository::getAllUsers();
        }
        else{
            $taskable_users = UserRepository::queryUserByNotAuthLevel(Roles::ADMIN->value);
        }

        header("HTTP/1.0 200 OK");
        render("snippets/tasks/edit", ['title' => "Edit Task", 'task' => $task, 'users' => $taskable_users]);
    }

    public function updateTask(){

        global $guard;

        $guard->hasAccess(Roles::EMPLOYEE);

        // Ensure person either owns the task, has the task assigned to them, or is an admin.

        $task = TaskRepository::getTaskById($_POST['id']);
        $current_user = SessionManager::get(SessionValues::USER_INFO->value);

        if (empty($task)) {
            header("HTTP/1.0 400 Bad Request");
            render('errors/404', ['title' => "Task Not Found"]);
            die();
        }

        $is_authorized = ($task->assigned_to == $current_user['id'] || $task->created_by == $current_user['id'] || $current_user['auth_level'] == Roles::ADMIN);

        if (!($is_authorized)) {
            header("HTTP/1.0 401 Unauthorized");
            render('errors/401', ['title' => "Access Denied"]);
            die();
        }


        $role_access = $guard->getAuthLevel();

        if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned']) && isset($_POST['dueDate']) && isset($_POST['status']) && isset($_POST['id'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $assigned = $_POST['assigned'];
            $dueDate = $_POST['dueDate'];
            $status = $_POST['status'];
            $comments = $_POST['comments'] ?? "";
            $id = $_POST['id'];

            $success = TaskRepository::updateTask($id, $title, $description, $status, $assigned, $dueDate, $comments);
            $success_route = '/dashboard/' . match($guard->getAuthLevel()) {
                Roles::ADMIN->value => 'admin',
                Roles::MANAGER->value => 'manager',
                Roles::EMPLOYEE->value => 'employee',
                default => 'employee'
            } . '?action=viewTasks';
            
            if ($success) {
                header("HTTP/1.0 301 OK");
                header("Location: $success_route");
                die();
            }

            else {
                header("HTTP/1.0 400 Bad Request");
                echo json_encode(["error" => "Bad Request"]);
                die();
            }
        } else{
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "No Data!"]);
            die();
        }
    }

    public function filterTasks() {
        global $guard;

        $guard->hasAccess(Roles::MANAGER);
        $role = $guard->getAuthLevel();

        $is_admin = $role == $guard->getAuthLevel();

        if (isset($_GET['filter']) && !empty($_GET['filter'])) {
            $filter = $_GET['filter'];
            if ($filter == "ALL" || $filter == "PENDING" || $filter == "PROGRESS" || $filter == "COMPLETED") {
                header("HTTP/1.0 200 Ok");
                header("Location: /dashboard/manager?action=filterTasks&filter=$filter");
                die();
            }
            
        }
        header("HTTP/1.0 400 Bad Request");
        echo json_encode(["error" => "Bad Request"]);
        die();
    }

}
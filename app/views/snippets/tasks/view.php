<?php

$current_user = SessionManager::get(SessionValues::USER_INFO->value);

if (is_null($current_user)) { 
    header("HTTP/1.0 401 Unauthorized");
    content_render('errors/401');
    die();
}

$auth_level = $current_user['auth_level'];

echo "<h2 class='subtitle'> View Task </h2>";

$tasks = match($auth_level) {
    Roles::ADMIN => $tasks = TaskRepository::getAllTasks(),
    Roles::MANAGER => $tasks = TaskRepository::getTasksByOwnerId($current_user['id']),
    Roles::EMPLOYEE => $tasks = TaskRepository::getTasksByUserId($current_user['id']),
    default => $tasks = [],
};

if (empty($tasks)) {
    echo "<p>No tasks found</p>";
    die();
}

if ($auth_level == Roles::ADMIN) {
    // Stats go here
    echo "<p>There are " . count($tasks) . " tasks in total</p>";
    echo "<p>There are " . count(array_filter($tasks, fn($task) => $task->status == Status::PENDING)) . " open tasks</p>";
    echo "<p>There are " . count(array_filter($tasks, fn($task) => $task->status == Status::PROGRESS)) . " tasks in progress</p>";
    echo "<p>There are " . count(array_filter($tasks, fn($task) => $task->status == Status::COMPLETED)) . " completed tasks</p>";
    echo "<hr>";
}

content_render('snippets/tasks/display', ['tasks' => $tasks]);


<?php 

if (!isset($tasks) || empty($tasks)) {
    header('HTTP/1.0 404 Not Found');
    content_render('errors/404', ['error' => 'No tasks found matching that criteria.']);
    die();
}

echo "<small> Click a task to edit it </small>";
foreach ($tasks as $task) {
    echo "<a href='/tasks/edit?id=$task->id'>
    <div class='box is-fullwidth my-3' style='cursor: pointer;'>
        <h3 class='title'> $task->title</h3>
        <p> $task->description</p>
        <div class='is-flex is-flex-wrap-wrap is-justify-content-space-between my-2'>
            <p> Status: " . $task->status->value . " </p>
            <p> Due Date: $task->due_date</p>
        </div>
        <div class='is-flex is-flex-wrap-wrap is-justify-content-space-between my-2'>
            <p> Assigned To: " . UserRepository::getUserById($task->assigned_to)->username . " </p>
            <p> Assigned By: " . UserRepository::getUserById($task->created_by)->username . "</p>
        </div>
    </div>
    </a>";
}

?>
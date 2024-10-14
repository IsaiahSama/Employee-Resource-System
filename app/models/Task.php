<?php 

require_once 'framework/Status.php';

class Task {

    public int $id;
    public string $title;
    public string $description;
    public Status $status;
    public int $assigned_to;
    public int $created_by;
    public string $due_date;
    public string $comments;

    public function __construct(array $task) {
        $this->id = $task['task_id'];
        $this->title = $task['title'];
        $this->description = $task['description'];
        $this->status = match($task['status']) { 
            Status::PENDING->value => Status::PENDING,
            Status::PROGRESS->value => Status::PROGRESS,
            Status::COMPLETED->value => Status::COMPLETED,
            default => Status::PENDING
        };
        $this->assigned_to = $task['assigned_to'];
        $this->created_by = $task['created_by'];
        $this->due_date = $task['due_date'];
        $this->comments = $task['comments'] ?? "";
    }
}
<h1 class="title">Edit Task</h1>

<?php 

    $user = SessionManager::get(SessionValues::USER_INFO->value);
    
    if (is_null($user)) { 
        header("HTTP/1.0 401 Unauthorized");
        content_render('errors/401');
        die();
    }
    
    $auth_level = $user['auth_level']->value;

    $non_manager_required = '';
    $non_manager_disabled = '';

    if ($auth_level <= Roles::EMPLOYEE->value) {
        $non_manager_required = 'readonly';
        $non_manager_disabled = 'disabled';
    }

?>

<form action="/tasks/edit" method="POST">
    <input type="hidden" id='id' name="id" value="<?php echo $task->id; ?>">
    <div class="field">
        <label class="label">Task Name:</label>
        <div class="control">
            <input class="input" type="text" name="title" value="<?php echo $task->title; ?>" required <?php echo $non_manager_required ?> >
        </div>
    </div>

    <div class="field">
        <label for="dueDate" class="label">Due:</label>
        <div class="control">
            <input class="input" type="date" name="dueDate" id="dueDate" value="<?php echo $task->due_date; ?>" required <?php echo $non_manager_required ?> >
        </div>
    </div>

    <div class="field">
        <label for="description" class="label">Task Description</label>
        <div class="control">
            <textarea name="description" id="description" class="textarea" required <?php echo $non_manager_required ?> ><?php echo $task->description; ?></textarea>
        </div>
    </div>

    <div class="field">
        <?php 
            if (isset($users)) {
                    echo '<label for="assigned" class="label">Assign task to:</label>';
                    echo '<div class="select">';
                        echo '<select name="assigned" id="assigned" class="select"' . $non_manager_required . '>';
                            foreach ($users as $user) {
                                if ($user->id == $task->assigned_to) {
                                    echo "<option selected value='" . $user->id . "'>" . $user->username . ' ( ' . $user->email . ' )</option>';
                                }
                                else{
                                    echo "<option $non_manager_disabled value='" . $user->id . "'>" . $user->username . ' ( ' . $user->email . ' )</option>';
                                }
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
            }
            else {
                echo "<p class='has-text-danger'>No employees found to assign the task to.</p>";
            }
        ?>
    </div>

    <div class="field">
        <label for="status" class="label" readonly>Status</label>
        <div class="control">
            <div class="select">
                <select name="status" id="status" required>
                    <option value="1" <?php if ($task->status == Status::PENDING) echo 'selected'; ?>>Pending</option>
                    <option value="2" <?php if ($task->status == Status::PROGRESS) echo 'selected'; ?>>In Progress</option>
                    <option value="3" <?php if ($task->status == Status::COMPLETED) echo 'selected'; ?>>Completed</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field">
        <label for="comments" class="label">Comments</label>
        <div class="control">
            <textarea name="comments" id="comments" class="textarea" required><?php echo $task->comments; ?></textarea>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="submit" value="Update Task" class="button is-primary">
        </div>
    </div>
</form>
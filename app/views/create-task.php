<h1 class='title'>Create Task Page</h1>
<hr>

<form action="/tasks/create" method="POST">
    <div class="field">
        <label class="label">Task Name:</label>
        <div class="control">
            <input class="input" type="text" name="title" placeholder="Task Name" required>
        </div>
    </div>

    <div class="field">
        <label for="dueDate" class="label">Due:</label>
        <div class="control">
            <input class="input" type="date" name="dueDate" id="dueDate" required>
        </div>
    </div>

    <div class="field">
        <label for="description" class="label">Task Description</label>
        <div class="control">
            <textarea name="description" id="description" class="textarea" required></textarea>
        </div>
    </div>

    <div class="field">
        <?php 
            if (isset($users)) {
                    echo '<label for="assigned" class="label">Assign task to:</label>';
                    echo '<div class="select">';
                        echo '<select name="assigned" id="assigned" class="select">';
                            foreach ($users as $user) {
                                echo '<option value="' . $user->id . '">' . $user->username . ' ( ' . $user->email . ' )</option>';
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
        <div class="control">
            <input type="submit" value="Create Task" class="button is-primary">
        </div>
    </div>
</form>
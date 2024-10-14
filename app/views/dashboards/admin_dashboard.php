<h2 class="subtitle">Welcome to your dashboard admin!</h2>
<p>What will you be doing today?</p>

<hr>
<h3 class="subtitle">User Management</h3>
<div class="is-flex is-flex-wrap-wrap is-justify-content-space-evenly my-2">

    <a href="/dashboard/admin?action=addUser">
        <button class="button is-primary">Add Employee</button>
    </a>

    <a href="/dashboard/admin?action=viewUsers">
        <button class="button is-primary">View All Users</button>
    </a>

</div>

<hr>
<h3 class="subtitle">Task Management</h3>

<div class="is-flex is-flex-wrap-wrap is-justify-content-space-evenly my-2">

    <a href="/tasks/create">
        <button class="button is-primary">Create Task</button>
    </a>

    <a href="/dashboard/admin?action=viewTasks">
        <button class="button is-primary">View All Tasks</button>
    </a>

</div>

<div class="container" id="resultsContainer">
    <?php 
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'addUser':
                    content_render('snippets/users/create');
                    break;
                case 'viewUsers':
                    content_render('snippets/users/view');
                    break;
                case 'viewTasks':
                    content_render('snippets/tasks/view');
                    break;
            }
        }
    ?>
</div>
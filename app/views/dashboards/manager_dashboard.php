<h2 class="subtitle">Welcome to your dashboard Manager!</h2>
<p>What will you be doing today?</p>

<hr>
<h3 class="subtitle">Task Management</h3>

<div class="is-flex is-flex-wrap-wrap is-justify-content-space-evenly my-2">

    <a href="/tasks/create">
        <button class="button is-primary">Create Task</button>
    </a>

    <a href="/dashboard/manager?action=viewTasks">
        <button class="button is-primary">View My Tasks</button>
    </a>

    <a href="/dashboard/manager?action=filterTasks">
        <button class="button is-primary">Filter Tasks</button>
    </a>

</div>

<div class="container" id="resultsContainer">
    <?php 
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'viewTasks':
                    content_render('snippets/tasks/view');
                    break;
                case 'filterTasks':
                    content_render('snippets/tasks/filter');
                    break;
            }
        }
    ?>
</div>
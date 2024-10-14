<hr>
<form action="/tasks/filter" method="GET">
    <div class="is-flex is-justify-content-space-around is-align-items-center">
        <div class="field">
            <label for="filter" class="label">Filter Condition</label>
            <div class="control">
                <div class="select">
                    <select name="filter" id="filter">
                        <option value="ALL" <?php if (isset($_GET['filter']) && !empty($_GET['filter']) && $_GET['filter'] == 'ALL') {echo ('selected');} ?>>All</option>
                        <option value="PENDING" <?php if (isset($_GET['filter']) && !empty($_GET['filter']) && $_GET['filter'] == 'PENDING') {echo ('selected');} ?>>Pending</option>
                        <option value="PROGRESS" <?php if (isset($_GET['filter']) && !empty($_GET['filter']) && $_GET['filter'] == 'PROGRESS') {echo ('selected');} ?>>In Progress</option>
                        <option value="COMPLETED" <?php if (isset($_GET['filter']) && !empty($_GET['filter']) && $_GET['filter'] == 'COMPLETED') {echo ('selected');} ?>>Completed</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button is-success">Apply Filter</button>
            </div>
        </div>
    </div>
</form>

<hr>

<?php 
global $guard;

$guard->hasAccess(Roles::MANAGER);
$user = SessionManager::get(SessionValues::USER_INFO->value);
$role = $guard->getAuthLevel();

$is_admin = $role == Roles::ADMIN->value;

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    if ($filter == "ALL") {
        content_render('snippets/tasks/view');
    }
    else {
        $tasks = match($filter){
            "PENDING" => TaskRepository::getTasksByStatus(Status::PENDING->value, $user['id'], $is_admin),
            "PROGRESS" => TaskRepository::getTasksByStatus(Status::PROGRESS->value, $user['id'], $is_admin),
            "COMPLETED" => TaskRepository::getTasksByStatus(Status::COMPLETED->value, $user['id'], $is_admin),
            default => []
        };
        TaskRepository::getTasksByStatus($filter, $user['id'], $is_admin);
        content_render('snippets/tasks/display', ['tasks' => $tasks]);
    }
}

?>
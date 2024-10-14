<div class="is-flex is-justify-content-space-between is-flex-wrap-wrap">
    <div class="is-flex is-flex-direction-column">
        <h1 class="title"><?php echo $title ?></h1>
        <a href='/dashboard'>Back to Dashboard Menu</a>
    </div>

    <?php
        $user = SessionManager::get(SessionValues::USER_INFO->value);

        if ($user) {
            $username = $user['username'];
            $auth_level = $user['auth_level']->value;
        }
    ?>

    <h2 class="subtitle">Welcome back, <span class="has-text-weight-bold"><?php echo $username ?? 'Guest' ?></span></h2>
    <?php
        if (isset($username) && isset($auth_level)) {
            $auth_map = array(Roles::GUEST->value => 'Unknown User', Roles::ADMIN->value => 'Admin', Roles::EMPLOYEE->value => 'Employee', Roles::MANAGER->value => 'Manager');
            echo "<div class='has-text-right'>";
                echo '<p>Current Role: ' . $auth_map[$auth_level ?? Roles::GUEST->value] . '</p>';
                echo '<a href="/logout">Logout</a>';
                echo "</div>";
            }
            else {
            echo "<div class='has-text-right'>";
                echo '<a href="/login">Login</a>';
            echo "</div>";
        }
    ?>
</div>
<hr>
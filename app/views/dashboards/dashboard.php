<section class="container">
    <p>You've made it to this generic dashboard. Either you don't have permissions to access anywhere else... or you're not logged in. Of course, you could just be visiting. If so, choose your destiny below!</p>
    <p>Note: you can only go to the dashboards you have permission to access.</p>
    <hr>
    <div class="is-flex is-flex-wrap-wrap is-justify-content-space-around">
        <a href=<?php echo (isset($auth_level) && $auth_level > 0) ? "/dashboard/employee" : "/401" ?>>
            <button class="button is-primary" <?php echo (isset($auth_level) && $auth_level > 0) ? "" : "disabled" ?>>Employee Dashboard</button>
        </a>
        <a href=<?php echo (isset($auth_level) && $auth_level > 1) ? "/dashboard/manager" : "/401" ?>>
            <button class="button is-link" <?php echo (isset($auth_level) && $auth_level > 1) ? "" : "disabled" ?>>Manager Dashboard</button>
        </a>
        <a href=<?php echo (isset($auth_level) && $auth_level > 2) ? "/dashboard/admin" : "/401" ?>>
            <button class="button is-danger" <?php echo (isset($auth_level) && $auth_level > 2) ? "" : "disabled" ?>>Admin Dashboard</button>
        </a>

    </div>
</section>
<?php 

class DashboardManagerController {

    public function index() {
        // Validate if the user is logged in
        global $guard;

        $guard->hasAccess(Roles::MANAGER);

        header("HTTP/1.0 200 OK");
        render("dashboards/manager_dashboard", ['header' => 'tpl/dashboardHeader.php', 'title' => "Manager Dashboard"]);
    }
}
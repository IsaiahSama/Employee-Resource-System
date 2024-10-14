<?php 

class DashboardEmployeeController {


    public function index() {
        // Validate if the user is logged in
        global $guard;

        $guard->hasAccess(Roles::EMPLOYEE);
        
        header("HTTP/1.0 200 OK");
        render("dashboards/employee_dashboard", ['header' => 'tpl/dashboardHeader.php', 'title' => "Employee Dashboard"]);
    }
}
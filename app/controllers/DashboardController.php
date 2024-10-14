<?php 

class DashboardController {

    private function isAdmin() : bool {
        return true;
    }

    public function index() {
        // Validate if the user is logged in

        header("HTTP/1.0 200 OK");
        render("dashboards/dashboard", ['header' => 'tpl/dashboardHeader.php', 'title' => "Generic Dashboard"]);
    }
}
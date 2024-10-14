<?php 

class DashboardAdminController {

    public function index() {
        // Validate if the user is logged in
        global $guard;

        $guard->hasAccess(Roles::ADMIN);

        header("HTTP/1.0 200 OK");
        render("dashboards/admin_dashboard", ['header' => 'tpl/dashboardHeader.php', 'title' => "Admin Dashboard"]);
    }

    public function deleteUser() {
        global $guard;

        $guard->hasAccess(Roles::ADMIN);

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            UserRepository::deleteUserById($id);
            header("HTTP/1.0 301 OK");
            header('Location: /dashboard/admin?action=viewUsers');
            die();
        }
    }
}
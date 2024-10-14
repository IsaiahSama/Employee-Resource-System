<?php 

require_once 'tpl/layout.php';

class ErrorController {

    public function error404() {
        render('errors/404', ['title' => "Page Not Found"]);
    }

    public function error401() {
        render('errors/401', ['title' => "Unauthorized"]);
    }

    public function error500() {
        render('errors/500', ['title' => "Internal Server Error"]);
    }
}
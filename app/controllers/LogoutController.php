<?php

class LogoutController{

    public function logout() {

        SessionManager::destroy();
        header("HTTP/1.0 301 OK");
        header("Location: /login?success=Successfully logged out");
        die();
    }
}
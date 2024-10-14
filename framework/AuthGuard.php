<?php

require_once 'framework/Roles.php';


class AuthGuard {
    
    public function hasAccess(Roles $minimumRole, bool $autoRedirect = true) {
        $user = SessionManager::get(SessionValues::USER_INFO->value);
        if (is_null($user) || $user['auth_level']->value < $minimumRole->value) {
            header("HTTP/1.0 401 Unauthorized");
            header('Location: /401');
            die();
        }
        
    }

    public function getAuthLevel() {
        $user = SessionManager::get(SessionValues::USER_INFO->value);
        if (is_null($user)) return Roles::GUEST->value;
        return $user['auth_level']->value ?? Roles::GUEST->value;
    }
}

$guard = new AuthGuard();
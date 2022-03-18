<?php

namespace Lib\Manager;

class UserManager
{
    public function isConnect()
    {
        return isset($_SESSION['user']);
    }
    public function disconnect()
    {
        session_unset();
        session_destroy();
    }


    public function getRole()
    {
        if (!isset($_SESSION['user'])) {
            return ROLE_GUEST;
        }
        return $_SESSION['user']['role'];
    }

    public function hasAccess(...$roles)
    {
        if (self::isConnect()) {
            $userRole = self::getRole();
        } else {
            $userRole = ROLE_GUEST;
        }
        return in_array($userRole, $roles);
    }
}
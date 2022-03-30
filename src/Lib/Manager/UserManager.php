<?php

namespace Lib\Manager;

class UserManager
{
    public function isConnect()
    {
        $session =((isset($_SESSION['user']))?true:false) ;
        return $session;
    }
    public function disconnect()
    {
        session_unset();
        session_destroy();
    }


    public function getRole()
    {
        $sessionrole = ((isset($_SESSION['user']))?true:false);
        if (!$sessionrole) {
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
<?php
namespace Lib\Manager;

use Lib\Manager\UserManager;

class PermissionsManager
{
    public function HasAccessToController($route){
        $sessionrole = ((isset($_SESSION['user']['role']))?$_SESSION['user']['role']:NULL);
        $usermanager = new UserManager();
        $userRole = $usermanager->isConnect() ? $sessionrole : ROLE_GUEST;
        $access = RoutesManager::getRoute($route);

        if(!isset($access['access']))
        {
            return true;
        }else{
            foreach ($access['access'] as $role)
            {
                if(constant($role) == $userRole)
                {
                    return true;
                }
            }
            return false;
        }
    }
}
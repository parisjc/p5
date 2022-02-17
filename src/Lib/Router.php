<?php

namespace Lib;

use Lib\Exceptions\EnvironnementException;
use Lib\Exceptions\NotFoundException;
//use Lib\Exceptions\PermissionException;
use Lib\Exceptions\RouteException;
use Lib\Manager\ExceptionsManager;
use Lib\Manager\RoutesManager;
use ReflectionMethod;

class Router
{
//    private PermissionsManager $perm;

    function __construct()
    {
//        $this->perm = $perm;
    }

    public function getRoute()
    {
        $url = rtrim($_SERVER['REQUEST_URI']);
        $url = str_replace('/P5', '', $url);
        $count = substr_count($url, '/');
        $routes = RoutesManager::getRoutes();

        foreach ($routes as $route) {
            if (substr_count($route, '/') != $count) {
                continue;
            }

            preg_match_all('/{(.*?)}/', $route, $matches, PREG_OFFSET_CAPTURE);
            $matches = $matches[0];

            if (count($matches) === 0) {
                if ($route === $url) {
                    $configRoute = RoutesManager::getRoute($route);

                    if(isset($configRoute['methods']))
                    {
                        if(!in_array($_SERVER['REQUEST_METHOD'], $configRoute['methods']))
                        {
                            ExceptionsManager::addException(new RouteException("Mauvaises méthodes d'accès à cette route (Current : ".$_SERVER['REQUEST_METHOD'].", Autorisées : ".implode(', ', $configRoute['methods']).")"));
                            return false;
                        }
                    }

                    if(isset($configRoute['envDev']))
                    {
                        if($configRoute['envDev'] && $_SERVER['APP_ENV'] !== "dev")
                        {
                            ExceptionsManager::addException(new EnvironnementException("Cette route n'est dispoble que en environnement de développement."));
                            return false;
                        }
                    }

                    $ctrName = "App\Controller\\".$configRoute['controller'];

                    if(!class_exists($ctrName)){
                        ExceptionsManager::addException(new NotFoundException("Le controller '$ctrName' n'est pas valide"));
                        return false;
                    }

                    $controller = new $ctrName();
                    $func = $configRoute['function'];

                    if(!method_exists($controller, $func)){
                        ExceptionsManager::addException(new NotFoundException("La méthode '$ctrName:$func' n'est pas valide"));
                        return false;
                    }

//                    if($this->perm->HasAccessToController($route)){
                        if($_SERVER['REQUEST_METHOD'] === "POST")
                        {
                            $orderedParam = array();
                            $reflection = new ReflectionMethod($controller, $func);
                            foreach($reflection->getParameters() AS $arg)
                            {
                                if($_POST[$arg->name])
                                    $orderedParam[$arg->name] = $_POST[$arg->name];
                                else
                                    $orderedParam[$arg->name] = null;
                            }
                            call_user_func_array(array($controller, $func), $orderedParam);
                        }else{
                            $controller->$func();
                        }
                        return true;
//                    }else{
//                        if(!isset($_SESSION['user'])) {
//                            $LoginC = new LoginController();
//                            $LoginC->DefaultAction();
//                        }
//                        else {
//                            ExceptionsManager::addException(new PermissionException("You don't have access to this page"));
//                        }
//                        return false;
//                    }
                } else {
                    continue;
                }
            } else {
                $currentPos = 0;
                $regex = '/';
                foreach ($matches as $match) {
                    $regex .= str_replace('/', '\/', substr($route, $currentPos, $match[1] - $currentPos));
                    $regex .= '(([\w]).*)';
                    $currentPos = $match[1] + strlen($match[0]);
                }
                $regex .= "/";

                if (preg_match($regex, $url)) {
                    $paramsURL = explode('/', $url);
                    $paramsRoute = explode('/', $route);
                    $param = array();

                    foreach ($paramsRoute as $index => $value)
                    {
                        if(preg_match('/{(.*?)}/', $value))
                        {
                            $param[str_replace(array('{', '}'), '', $value)] = $paramsURL[$index];
                        }
                    }

                    $configRoute = RoutesManager::getRoute($route);

                    if(isset($configRoute['methods']))
                    {
                        if(!in_array($_SERVER['REQUEST_METHOD'], $configRoute['methods']))
                        {
                            ExceptionsManager::addException(new RouteException("Mauvaises méthodes d'accès à cette route (Current : ".$_SERVER['REQUEST_METHOD'].", Autorisées : ".implode(', ', $configRoute['methods']).")"));
                            return false;
                        }
                    }

                    if(isset($configRoute['envDev']))
                    {
                        if($configRoute['envDev'] && $_SERVER['APP_ENV'] !== "dev")
                        {
                            ExceptionsManager::addException(new EnvironnementException("Cette route n'est dispoble que en environnement de développement."));
                            return false;
                        }
                    }

                    $ctrName = "App\Controller\\".$configRoute['controller'];

                    if(!class_exists($ctrName)){
                        ExceptionsManager::addException(new NotFoundException("Le controller '$ctrName' n'est pas valide"));
                        return false;
                    }

                    $controller = new $ctrName();
                    $func = $configRoute['function'];

                    if(!method_exists($controller, $func)){
                        ExceptionsManager::addException(new NotFoundException("La méthode '$ctrName:$func' n'est pas valide"));
                        return false;
                    }

//                    if($this->perm->HasAccessToController($route)){
                        $orderedParam = array();
                        $reflection = new ReflectionMethod($controller, $func);
                        foreach($reflection->getParameters() AS $arg)
                        {
                            if(isset($param[$arg->name]))
                                $orderedParam[$arg->name] = $param[$arg->name];
                            else
                                $orderedParam[$arg->name] = null;
                        }
                        call_user_func_array(array($controller, $func), $orderedParam);
                        return true;
//                    }else{
//                        if(!isset($_SESSION['user'])) {
//                            $LoginC = new LoginController();
//                            $LoginC->DefaultAction();
//                        }
//                        else
//                        {
//                            ExceptionsManager::addException(new PermissionException("You don't have access to this page"));
//                        }
//                        return false;
//                    }
                }
            }
        }
        ExceptionsManager::addException(new NotFoundException("Aucune route ne correspond à : ".$url));
        ExceptionsManager::showErrorPage();
    }
}
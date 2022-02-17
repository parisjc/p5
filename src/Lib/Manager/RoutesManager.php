<?php

namespace Lib\Manager;

use Lib\Exceptions\RouteException;

class RoutesManager
{
    private static array $routesList = array();

    public static function addRoute(string $route, array $data)
    {
        if(empty(self::$routesList[$route])) {
            self::$routesList[$route] = [
                'controller' => $data['controller'],
                'function' => $data['function'],
                'access' => $data['access'] ?? null,
                'envdev' => $data['envDev'] ?? null,
                'methods' => $data['methods'] ?? null
            ];
        }else{
            ExceptionsManager::addException( new RouteException('Route '.$route.' already exist'));
        }
    }

    public static function getRoutes()
    {
        return array_keys(self::$routesList);
    }

    public static function getRoute($route)
    {
        return self::$routesList[$route];
    }
}
<?php

namespace Lib;

use Lib\BDD\BDD;
use Lib\Manager\ExceptionsManager;
use Lib\Manager\PermissionsManager;
use Lib\Manager\RoutesManager;
use RuntimeException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Application
{
    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');
        define("TWIG_PATH", "./../src/App/Twig/");

        error_reporting(E_ALL);

        if (version_compare(phpversion(), '7.4.2', '<')) {
            throw new RuntimeException("Vous n'avez pas la bonne version de PHP (minimal: 7.4.2,  current: ".phpversion().")");
        }
        session_start();

        $config = $this->loadConfig();
        BDD::init($config['database']);
        $perm = new PermissionsManager();
        foreach ($config['routes'] as $route => $data)
        {
            RoutesManager::addRoute($route, $data);
        }

        $router = new Router($perm);
        $error = $router->getRoute();
        if(isset($error) && !$error)
        {
            ExceptionsManager::showErrorPage();
        }else{
            ExceptionsManager::checkError();
        }
    }

    private function loadConfig()
    {
        $finalConfig = array();

        try{
            $db = Yaml::parseFile(Autoload::getRootPath().'config/database.yml');
            $config = Yaml::parseFile(Autoload::getRootPath().'config/config.yml');
            $routing = Yaml::parseFile(Autoload::getRootPath().'config/routing.yml');
            foreach ($config['access'] as $role => $id) {
                define($role, $id);
            }
            $finalConfig =  array_merge($db,$config,$routing);
        } catch (ParseException $exception) {
            ExceptionsManager::addException( new RuntimeException('Unable to parse the YAML string: %s', $exception->getMessage()));
        }

        return $finalConfig;
    }
}
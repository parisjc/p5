<?php

namespace Lib;

use Lib\BDD\BDD;
use Lib\Manager\ExceptionsManager;
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
//        foreach ($config['routes'] as $route => $data)
//        {
//            RoutesManager::addRoute($route, $data);
//        }
        BDD::init($config['database']);
        foreach ($config['routes'] as $route => $data)
        {
            RoutesManager::addRoute($route, $data);
        }

        $router = new Router();
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
            $routing = Yaml::parseFile(Autoload::getRootPath().'config/routing.yml');
            $finalConfig =  array_merge($db,$routing);
        } catch (ParseException $exception) {
            ExceptionsManager::addException( new RuntimeException('Unable to parse the YAML string: %s', $exception->getMessage()));
        }

        return $finalConfig;
    }
}
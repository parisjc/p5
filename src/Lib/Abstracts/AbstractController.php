<?php
namespace Lib\Abstracts;

use Lib\Autoload;
use Lib\Manager\ExceptionsManager;
use Lib\Manager\RessourceManager;
use Lib\Twig\assetExtension;
use Lib\Twig\DebugExtension;
use Lib\Twig\translateExtension;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

abstract class AbstractController
{
    protected Environment $twig;

    function __construct()
    {
        $loader = new FilesystemLoader(TWIG_PATH);
        $this->twig = new Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);
//        $this->twig->addGlobal('app', [
//            'debug' => $_SERVER['DEBUG'],
//            'env' => $_SERVER['APP_ENV']
//        ]);

        $this->twig->addExtension(new assetExtension());
        $this->twig->addExtension(new translateExtension());
        $this->twig->addExtension(new DebugExtension());
//        $this->twig->addFunction(new TwigFunction('hasAccess', array(new UserManager(), 'hasAccess')));
//        if($_SERVER['DEBUG'] === true) {
//            $this->twig->addExtension(new DebugExtension());
//        }
    }


    protected function render($tpl, array $arr = []){
        try {
            $arr['host'] = Autoload::getHost();
            return $this->twig->render($tpl, $arr);

        } catch (LoaderError|SyntaxError|RuntimeError $e) {
            ExceptionsManager::addException($e);
            return $e->getMessage();
        }
    }
}
<?php
namespace Lib\Abstracts;

use Lib\Autoload;
use Lib\Manager\ExceptionsManager;
use Lib\Manager\RessourceManager;
//use Lib\Manager\UserManager;
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
    private bool $refreshHeader = false;

    function __construct()
    {
        $loader = new FilesystemLoader(TWIG_PATH);
        $this->twig = new Environment($loader);
//        $this->twig->addGlobal('session', $_SESSION);
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

    protected function refreshHeader()
    {
        $this->refreshHeader = true;
    }

    protected function render($tpl, array $arr = []){
        try {
            $arr['host'] = Autoload::getHost();
//            $arr['_languages'] = LanguagesRepository::getAll();

//            if(isset($_COOKIE['langue']))
//            {
//                LanguagesRepository::setLanguage($_COOKIE['langue']);
//            }
//            $arr['_languagescookie'] = LanguagesRepository::getLanguage();
            if(isset($_POST['refreshHeader']) && $_POST['refreshHeader'] === "true")
            {
                $this->refreshHeader = true;
            }

            if(isset($_POST['update']) && $_POST['update'] === "true")
            {
                $template = $this->twig->load($tpl);

                $send = array();

                if($template->hasBlockNoParent('css')){
                    $css = $template->renderBlock('css',$arr);
                    $send['css'] = RessourceManager::getRessources('css', $css);
                }

                if($template->hasBlockNoParent('js')){
                    $js = $template->renderBlock('js',$arr);
                    $send['js'] = RessourceManager::getRessources('js', $js);
                }

                if($template->hasBlockNoParent('header') || $this->refreshHeader === true){
                    $send['header'] = $template->renderBlock('header',array_merge( array(
                        '_languages' => $arr['_languages'],
                        '_languagescookie' => $arr['_languagescookie']
                    ),$arr));
                }

                if($template->hasBlockNoParent('footer')){
                    $send['footer'] = $template->renderBlock('footer',$arr);
                }

                if($template->hasBlockNoParent('footer')){
                    $send['title'] = 'Loups Garous | '.$template->renderBlock('page_title',$arr);
                }

                $send['content'] = $template->renderBlock('body',$arr);


                echo json_encode($send);
            }
            elseif(isset($_POST['refresh']) && $_POST['refresh'] === "true")
            {
                $template = $this->twig->load($tpl);

                $send = array();

                if($template->hasBlock('header')){
                    $send['header'] = $template->renderBlock('header',array_merge( array(
                        '_languages' => $arr['_languages'],
                        '_languagescookie' => $arr['_languagescookie']
                    ),$arr));
                }

                if($template->hasBlock('footer')){
                    $send['footer'] = $template->renderBlock('footer',$arr);
                }

                $send['content'] = $template->renderBlock('body',$arr);

                echo json_encode($send);
            }else{
                return $this->twig->render($tpl, $arr);
            }
        } catch (LoaderError $e) {
            ExceptionsManager::addException($e);
            return $e->getMessage();
        } catch (RuntimeError $e) {
            ExceptionsManager::addException($e);
            return $e->getMessage();
        } catch (SyntaxError $e) {
            ExceptionsManager::addException($e);
            return $e->getMessage();
        }
    }
}
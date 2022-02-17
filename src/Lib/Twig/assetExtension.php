<?php

namespace Lib\Twig;

use Lib\Manager\RessourceManager;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class assetExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return array (
            new TwigFunction('asset', array($this, 'asset')),
        );
    }

    public function asset($file)
    {
        $ext = strrchr($file, '.');
        switch ($ext)
        {
            case '.css':
                $file = '/P5/build/css/'.$file;
                RessourceManager::addCSSRessource($file);
                $tag = '<link href="'.$file.'" rel="stylesheet"/>';
                return new Markup( $tag, 'UTF-8' );
            case '.js':
                $file = '/P5/build/js/'.$file;
                RessourceManager::addJSRessource($file);
                $tag = '<script src="'.$file.'"></script>';
                return new Markup( $tag, 'UTF-8' );
            default:
                $file = '/P5/build/imgs/'.$file;
                return $file;
        }
    }

    public function getName()
    {
        return 'Twig:Asset';
    }

}
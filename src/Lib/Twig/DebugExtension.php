<?php


namespace Lib\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DebugExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return array (
            new TwigFunction('dump', array('Symfony\Component\VarDumper\VarDumper', 'dump')),
        );
    }

    public function getName()
    {
        return 'Twig:Debug';
    }

}
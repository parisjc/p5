<?php


namespace Lib\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class isConnectedExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return array (
            new TwigFunction('isConnect', array('Lib\Manager\UserManager', 'isConnect'))
        );
    }

    public function getName()
    {
        return 'Twig:isConnect';
    }

}
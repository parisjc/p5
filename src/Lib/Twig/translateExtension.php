<?php

namespace Lib\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class translateExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return array (
            new TwigFunction('translate', array('App\Repository\LanguagesRepository', 'getTranslate')),
        );
    }

    public function getName()
    {
        return 'Twig:Translate';
    }

}
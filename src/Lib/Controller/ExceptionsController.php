<?php

namespace Lib\Controller;

use Lib\Abstracts\AbstractController;
use Lib\Manager\ExceptionsManager;

class ExceptionsController extends AbstractController
{
    public function DefaultAction()
    {
        echo $this->render('Base/error1.twig', array(
            '_exceptions' => ExceptionsManager::getExceptions(),
            '_critical' => true
        ));
    }
}
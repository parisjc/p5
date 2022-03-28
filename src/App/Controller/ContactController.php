<?php

namespace App\Controller;

use Lib\Abstracts\AbstractController;

class ContactController extends AbstractController
{
    public function DefaultAction()
    {
        $user = (isset($_SESSION['user']))?$_SESSION['user']:"";
        echo $this->render('home/contact.twig',array('user'=>$user));
    }

    public function Wecontact()
    {
        echo true;
    }

}
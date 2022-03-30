<?php

namespace App\Controller;

use http\Env\Request;
use Lib\Abstracts\AbstractController;
use App\Repository\UsersRepository;
use Lib\Manager\UserManager;

class LoginController extends AbstractController
{
    protected UsersRepository $userRepo;
    protected UserManager $userManager;

    function __construct()
    {
        parent::__construct();
        $this->userRepo= new UsersRepository();
        $this->userManager = new UserManager();
    }
    public function DefaultAction()
    {
        printf($this->render('home/login.twig'));
    }

    public function LoginAction()
    {
        printf($this->render('home/sign.twig'));
    }

    public function ValidLogin($username,$pwd)
    {
        $res = $this->userRepo->ValidLogin($username,$pwd);
         printf(json_encode($res));

    }

    public function DisconnectAction()
    {
        $this->userManager->disconnect();
        header('Location: /P5');
    }

}
<?php

namespace App\Controller;

use http\Env\Request;
use Lib\Abstracts\AbstractController;
use App\Repository\UsersRepository;

class LoginController extends AbstractController
{
    protected UsersRepository $userRepo;
    function __construct()
    {
        parent::__construct();
        $this->userRepo= new UsersRepository();
    }
    public function DefaultAction()
    {
        echo $this->render('home/login.twig');
    }

    public function ValidLoginAction($username,$pwd)
    {
        $res = $this->userRepo->ValidLogin($username,$pwd);
        echo json_encode($res);

    }

    private function CreCompteAction ()
    {
        $options = [
            'cost' => 12,
        ];
        echo password_hash("mdp", PASSWORD_BCRYPT, $options);
    }
}
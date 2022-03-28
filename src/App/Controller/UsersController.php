<?php

namespace App\Controller;


use http\Env\Request;
use Lib\Abstracts\AbstractController;
use App\Repository\UsersRepository;

class UsersController extends AbstractController
{
    protected UsersRepository $userRepo;

    function __construct()
    {
        parent::__construct();
        $this->userRepo= new UsersRepository();
    }

    public function SaveUsers($nom,$prenom,$email,$username,$mdp)
    {
        $res = $this->userRepo::saveusers($nom,$prenom,$email,$username,$mdp);
        echo json_encode($res);
    }
}
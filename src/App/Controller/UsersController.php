<?php

namespace App\Controller;


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

    public function DefaultAction()
    {
        $res = $this->userRepo::getAll();
        echo $this->render('home/utilisateur.twig',array('users'=>$res));
    }

    /**
     * @throws \Exception
     */
    public function SaveUsers($nom, $prenom, $email, $username, $mdp)
    {
        $res = $this->userRepo::saveusers($nom,$prenom,$email,$username,$mdp);
        echo json_encode($res);
    }

    public function UpdateActifByUser($id,$actif)
    {
        $user = $this->userRepo::setUpdateAtifByUser($id,$actif);
        echo json_encode($user);
    }

    public function SupUserById($id)
    {
        $user = $this->userRepo::setSuppUser($id);
        echo json_encode($user);
    }
}
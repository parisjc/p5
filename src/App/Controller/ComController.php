<?php

namespace App\Controller;

use Lib\Abstracts\AbstractController;
use App\Repository\ComRepository;

class ComController extends AbstractController
{
    protected ComRepository $comRepo;
    function __construct()
    {
        parent::__construct();
        $this->comRepo= new ComRepository();
    }

    public function DefaultAction()
    {
        $comenable = $this->comRepo::getComByEnable();
        $comactif = $this->comRepo::getComByActif();
        echo $this->render('home/commentaire.twig',array('comenable'=>$comenable, 'comactif'=>$comactif));
    }

    public function UpdateComsActifById($id,$actif)
    {
        $res = $this->comRepo::setComValidById($id,$actif);
        echo json_encode($res);
    }

    public function SupComById($id)
    {
        $res = $this->comRepo::setSuppCom($id);
        echo json_encode($res);
    }

    public function SaveCom($id_post,$nom,$prenom,$com)
    {
        $res = $this->comRepo::SaveCom($id_post,$nom,$prenom,$com);
        echo json_encode($res);
    }

}
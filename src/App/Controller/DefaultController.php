<?php
namespace App\Controller;

use http\Env\Request;
use Lib\Abstracts\AbstractController;
use App\Repository\PostRepository;

class DefaultController extends AbstractController
{
    protected PostRepository $poseRepo;
    function __construct()
    {
        parent::__construct();
        $this->poseRepo= new PostRepository();
    }
    public function DefaultAction()
    {
        dump($_SESSION);
        $postActifLimit =$this->poseRepo::getPostActifLimit();
        $posts =$this->poseRepo::getListPost();
        echo $this->render('home/index.twig',array('postActifLimit'=>$postActifLimit,'posts'=>$posts));
    }

}
<?php

namespace App\Controller;

use App\Repository\ComRepository;
use http\Env\Request;
use Lib\Abstracts\AbstractController;
use App\Repository\PostRepository;

class PostController extends AbstractController
{
    protected PostRepository $poseRepo;
    protected ComRepository $comRepo;
    function __construct()
    {
        parent::__construct();
        $this->poseRepo= new PostRepository();
        $this->comRepo= new ComRepository();
    }

    public function DefaultAction($id)
    {
        $post = $this->poseRepo::getById($id);
        $com = $this->comRepo->getComValidByPost($id);
//        dump($com);
        echo $this->render('home/post.twig',array('post'=>$post,'comments'=>$com));
    }

    public function UpdatePost($id,$content)
    {
        dump($id);
        dump($content);
        $post = $this->poseRepo::setUpdatePost($id,$content);
        var_dump($post);
    }
}
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
        if(isset($_SESSION['user'])) {
            ($post->getid_users()->getusername() == $_SESSION['user']['username']) ? $editor = true : $editor = false;
        }
        else
        {
            $editor=false;
        }
        echo $this->render('home/post.twig',array('post'=>$post,'comments'=>$com,'editor'=>json_encode($editor)));
    }

    public function PostByUserAction()
    {
        $post = $this->poseRepo::getListByUser($_SESSION['user']['id']);
        echo $this->render('home/article.twig',array('posts'=>$post));
    }

    public function UpdateActifByPost($id,$actif)
    {
        $post = $this->poseRepo::setUpdateAtifByPost($id,$actif);
        echo json_encode($post);
    }
    public function UpdatePost($id,$content)
    {
        $post = $this->poseRepo::setUpdatePost($id,$content);
        echo json_encode($post);
    }
}
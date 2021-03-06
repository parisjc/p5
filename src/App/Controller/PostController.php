<?php

namespace App\Controller;

use Lib\Abstracts\AbstractController;
use App\Repository\ComRepository;
use App\Repository\PostRepository;
use App\Repository\CatRepository;

class PostController extends AbstractController
{
    protected PostRepository $poseRepo;
    protected ComRepository $comRepo;
    protected CatRepository $catRepo;
    function __construct()
    {
        parent::__construct();
        $this->poseRepo= new PostRepository();
        $this->comRepo= new ComRepository();
        $this->catRepo = new CatRepository();
    }

    public function DefaultAction($id)
    {
        (isset($_GET['vue']))?$vue = $_GET['vue']:$vue=true;
        $post = $this->poseRepo::getById($id);
        $com = $this->comRepo->getComValidByPost($id);
        if(isset($_SESSION['user'])) {
            ($post->getid_users()->getusername() == $_SESSION['user']['username']) ? $editor = true : $editor = false;
        }
        else
        {
            $editor=false;
        }
        echo $this->render('home/post.twig',array('post'=>$post, 'comments'=>$com, 'editor'=>json_encode($editor), 'vue'=>json_encode($vue)));
    }

    public function PostByUserAction()
    {
        if($_SESSION['user']['role']==ROLE_ADMIN)
        {
            $post = $this->poseRepo::getListPostAdmin();
        }
        else
        {
            $post = $this->poseRepo::getListByUser($_SESSION['user']['id']);
        }
        $cat = $this->catRepo::getCat();
        printf($this->render('home/article.twig',array('posts'=>$post,'cats'=>$cat)));
    }

    public function UpdateActifByPost($id,$actif)
    {
        $post = $this->poseRepo::setUpdateAtifByPost($id,$actif);
        echo json_encode($post);
    }
    public function UpdatePost($id,$title,$content)
    {
        $post = $this->poseRepo::setUpdatePost($id,$title,$content);
        echo json_encode($post);
    }

    public function SupPostById($id)
    {
        $post = $this->poseRepo::setSuppPost($id);
        echo json_encode($post);
    }

    public function NewPost($title,$summary,$categorie)
    {
        $post = $this->poseRepo::setNewPost($title,$summary,$categorie);
        if($post!=false)
        {
            $res = array('result'=>true,'newid'=>$post);
        }
        else{
            $res = array('result'=>false);
        }
        echo json_encode($res);
    }
}
<?php
namespace App\Repository;

use App\Entity\Post;
use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;
use Lib\Exceptions\BDDException;
use Lib\Exceptions\RepositoryException;
use Lib\Manager\ExceptionsManager;
use PDO;

class PostRepository extends AbstractEntityRepository
{
    protected static string $table = 'post';
    protected static string $classMapped = Post::class;

    public static function getPostActifLimit()
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = 'SELECT * FROM '.static::$table.' WHERE actif=1 ORDER BY id DESC LIMIT 2';

        $prep = $bdd::prepare($query);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return self::fetch($prep);
        }else{
            return false;
            }
    }

    public static function getListPost()
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = 'SELECT id,title,summary FROM '.static::$table.' WHERE actif=1 ORDER BY id DESC';

        $prep = $bdd::prepare($query);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return self::fetch($prep);
        }else{
            return false;
        }
    }

    public static function getListPostAdmin()
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = 'SELECT post.id,title,creation_date,actif,libelle_cat FROM '.static::$table.',categorie WHERE categorie.id=post.id_cat ORDER BY post.id DESC';
        $prep = $bdd::prepare($query);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return self::fetch($prep);
        }else{
            ExceptionsManager::addException(new BDDException($bdd::getDB()->errorInfo()[2]));
        }
    }

    public static function getListByUser($id_users)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = 'SELECT post.id,title,creation_date,actif,libelle_cat FROM '.static::$table.',categorie WHERE id_users='.$id_users.' AND categorie.id=post.id_cat ORDER BY post.id DESC';
        $prep = $bdd::prepare($query);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return self::fetch($prep);
        }else{
            return false;
        }
    }

    public static function setUpdatePost($id,$title,$content)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "UPDATE post SET content=:content, title=:title WHERE id=$id";

        $prep = $bdd::prepare($query);
        $prep->bindParam('content',$content,PDO::PARAM_STR_NATL);
        $prep->bindParam('title',$title,PDO::PARAM_STR);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            return false;
        }
    }

    public static function setUpdateAtifByPost($id,$actif)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "UPDATE post SET actif = :actif WHERE id=$id";

        $prep = $bdd::prepare($query);
        $prep->bindParam('actif',$actif,PDO::PARAM_BOOL);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            return false;
        }
    }

    public static function setSuppPost($id)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "DELETE FROM post WHERE id=:id";

        $prep = $bdd::prepare($query);
        $prep->bindParam('id',$id,PDO::PARAM_INT);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            return false;
        }
    }

    public static function setUpdatePostimg($id,$filename)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "UPDATE post SET img=:img WHERE id=:id";

        $prep = $bdd::prepare($query);
        $prep->bindParam('id',$id,PDO::PARAM_INT);
        $prep->bindParam('img',$filename,PDO::PARAM_STR);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            return false;
        }
    }

    public static function setNewPost($title,$summary,$categorie)
    {
        $id_users = $_SESSION['user']['id'];
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "INSERT INTO post (title,summary,id_users,id_cat) VALUES (:title,:summary,:id_users,:id_cat)";

        $prep = $bdd::prepare($query);
        $prep->bindParam('title',$title,PDO::PARAM_STR);
        $prep->bindParam('summary',$summary,PDO::PARAM_STR);
        $prep->bindParam('id_users',$id_users,PDO::PARAM_INT);
        $prep->bindParam('id_cat',$categorie,PDO::PARAM_INT);

        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return (int)$bdd::lastInsert();
        }else{
            return false;
        }
    }
}
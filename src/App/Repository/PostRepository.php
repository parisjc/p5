<?php
namespace App\Repository;
use App\Entity\Post;
use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;
use Lib\Exceptions\BDDException;
use Lib\Exceptions\RepositoryException;
use Lib\Manager\ExceptionsManager;

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
            ExceptionsManager::addException(new BDDException($bdd::getDB()->errorInfo()[2]));
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
            ExceptionsManager::addException(new BDDException($bdd::getDB()->errorInfo()[2]));
        }
    }

    public static function getListByUser($id_users)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = 'SELECT id,title,creation_date,actif FROM '.static::$table.' WHERE id_users='.$id_users.' ORDER BY id DESC';
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

    public static function setUpdatePost($id,$content)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "UPDATE post SET content=\"$content\" WHERE id=$id";

        $prep = $bdd::prepare($query);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            ExceptionsManager::addException(new BDDException($bdd::getDB()->errorInfo()[2]));
        }
    }

    public static function setUpdateAtifByPost($id,$actif)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "UPDATE post SET actif=$actif WHERE id=$id";

        $prep = $bdd::prepare($query);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            ExceptionsManager::addException(new BDDException($bdd::getDB()->errorInfo()[2]));
        }
    }
}
<?php
namespace App\Repository;

use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;
use Lib\Exceptions\BDDException;
use Lib\Exceptions\RepositoryException;
use Lib\Manager\ExceptionsManager;
use App\Entity\Comments;
use PDO;

class ComRepository extends AbstractEntityRepository
{
    protected static string $table = 'comments';
    protected static string $classMapped = Comments::class;

    public static function getComByEnable()
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "SELECT * FROM ".static::$table." WHERE valid=:valid ORDER BY id DESC ";
        $val =false;
        $prep = $bdd::prepare($query);
        $prep->bindParam('valid',$val,PDO::PARAM_BOOL);

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
    public static function getComByActif()
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "SELECT * FROM ".static::$table." WHERE valid=:valid ORDER BY id DESC ";
        $val = true;
        $prep = $bdd::prepare($query);
        $prep->bindParam('valid',$val,PDO::PARAM_BOOL);

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



    public function getComValidByPost($id_post)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "SELECT * FROM ".static::$table." WHERE id_post=:id_post AND valid=1  ORDER BY id DESC ";

        $prep = $bdd::prepare($query);
        $prep->bindParam('id_post',$id_post,PDO::PARAM_INT);

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

    public static function setComValidById($id,$actif)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "UPDATE comments SET valid = :actif WHERE id=:id";

        $prep = $bdd::prepare($query);
        $prep->bindParam('actif',$actif,PDO::PARAM_BOOL);
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

    public static function setSuppCom($id)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "DELETE FROM comments WHERE id=:id";

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
    public static function SaveCom($id_post,$nom,$prenom,$com)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "INSERT INTO comments(nom,prenom,comment,id_post) VALUES(:nom,:prenom,:comment,:id_post)";

        $prep = $bdd::prepare($query);
        $prep->bindParam('nom',$nom,PDO::PARAM_STR);
        $prep->bindParam('prenom',$prenom,PDO::PARAM_STR);
        $prep->bindParam('comment',$com,PDO::PARAM_STR);
        $prep->bindParam('id_post',$id_post,PDO::PARAM_INT);
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


}
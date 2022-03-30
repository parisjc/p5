<?php
namespace App\Repository;

use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;
use App\Entity\Comments;
use PDO;

class ComRepository extends AbstractEntityRepository
{
    protected static string $table = 'comments';
    protected static string $classMapped = Comments::class;

    public static function getComByEnable()
    {
        $query = "SELECT * FROM ".static::$table." WHERE valid=0 ORDER BY id DESC ";
        $prep = BDD::prepare($query);
        return self::execprepa($prep,true);
    }
    public static function getComByActif()
    {
        $query = "SELECT * FROM ".static::$table." WHERE valid=1 ORDER BY id DESC ";
        $prep = BDD::prepare($query);
        return self::execprepa($prep,true);
    }



    public function getComValidByPost($id_post)
    {
        $query = "SELECT * FROM ".static::$table." WHERE id_post=:id_post AND valid=1  ORDER BY id DESC ";

        $prep = BDD::prepare($query);
        $prep->bindParam('id_post',$id_post,PDO::PARAM_INT);
        return self::execprepa($prep,true);
    }

    public static function setComValidById($id,$actif)
    {
        $query = "UPDATE comments SET valid = :actif WHERE id=:id";

        $prep = BDD::prepare($query);
        $prep->bindParam('actif',$actif,PDO::PARAM_BOOL);
        $prep->bindParam('id',$id,PDO::PARAM_INT);

        return self::execprepa($prep);
    }

    public static function setSuppCom($id)
    {
        $query = "DELETE FROM comments WHERE id=:id";

        $prep = BDD::prepare($query);
        $prep->bindParam('id',$id,PDO::PARAM_INT);
        return self::execprepa($prep);
    }
    public static function SaveCom($id_post,$nom,$prenom,$com)
    {
        $query = "INSERT INTO comments(nom,prenom,comment,id_post) VALUES(:nom,:prenom,:comment,:id_post)";

        $prep = BDD::prepare($query);
        $prep->bindParam('nom',$nom,PDO::PARAM_STR);
        $prep->bindParam('prenom',$prenom,PDO::PARAM_STR);
        $prep->bindParam('comment',$com,PDO::PARAM_STR);
        $prep->bindParam('id_post',$id_post,PDO::PARAM_INT);
        return self::execprepa($prep);
    }


}
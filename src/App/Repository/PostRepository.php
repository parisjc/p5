<?php
namespace App\Repository;

use App\Entity\Post;
use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;
use PDO;

class PostRepository extends AbstractEntityRepository
{
    protected static string $table = 'post';
    protected static string $classMapped = Post::class;

    public static function getPostActifLimit()
    {
        $query = 'SELECT * FROM '.static::$table.' WHERE actif=1 ORDER BY id DESC LIMIT 2';

        $prep = BDD::prepare($query);
        return self::execprepa($prep,true);
    }

    public static function getListPost()
    {
        $query = 'SELECT id,title,summary FROM '.static::$table.' WHERE actif=1 ORDER BY id DESC';

        $prep = BDD::prepare($query);
        return self::execprepa($prep,true);
    }

    public static function getListPostAdmin()
    {
        $query = 'SELECT post.id,title,creation_date,actif,libelle_cat FROM '.static::$table.',categorie WHERE categorie.id=post.id_cat ORDER BY post.id DESC';

        $prep = BDD::prepare($query);
        return self::execprepa($prep,true);
    }

    public static function getListByUser($id_users)
    {
        $query = 'SELECT post.id,title,creation_date,actif,libelle_cat FROM '.static::$table.',categorie WHERE id_users='.$id_users.' AND categorie.id=post.id_cat ORDER BY post.id DESC';

        $prep = BDD::prepare($query);
        return self::execprepa($prep,true);
    }

    public static function setUpdatePost($id,$title,$content)
    {
        $query = "UPDATE post SET content=:content, title=:title WHERE id=$id";

        $prep = BDD::prepare($query);
        $prep->bindParam('content',$content,PDO::PARAM_STR_NATL);
        $prep->bindParam('title',$title,PDO::PARAM_STR);
        return self::execprepa($prep);
    }

    public static function setUpdateAtifByPost($id,$actif)
    {
        $query = "UPDATE post SET actif = :actif WHERE id=$id";

        $prep = BDD::prepare($query);
        $prep->bindParam('actif',$actif,PDO::PARAM_BOOL);
        return self::execprepa($prep);
    }

    public static function setSuppPost($id)
    {
        $query = "DELETE FROM post WHERE id=:id";

        $prep = BDD::prepare($query);
        $prep->bindParam('id',$id,PDO::PARAM_INT);
        return self::execprepa($prep);
    }

    public static function setUpdatePostimg($id,$filename)
    {
        $query = "UPDATE post SET img=:img WHERE id=:id";

        $prep = BDD::prepare($query);
        $prep->bindParam('id',$id,PDO::PARAM_INT);
        $prep->bindParam('img',$filename,PDO::PARAM_STR);
        return self::execprepa($prep);
    }

    public static function setNewPost($title,$summary,$categorie)
    {
        $id_users = $_SESSION['user']['id'];

        $query = "INSERT INTO post (title,summary,id_users,id_cat) VALUES (:title,:summary,:id_users,:id_cat)";

        $prep = BDD::prepare($query);
        $prep->bindParam('title',$title,PDO::PARAM_STR);
        $prep->bindParam('summary',$summary,PDO::PARAM_STR);
        $prep->bindParam('id_users',$id_users,PDO::PARAM_INT);
        $prep->bindParam('id_cat',$categorie,PDO::PARAM_INT);

        return self::execprepa($prep);
    }
}
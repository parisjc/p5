<?php
namespace App\Repository;

use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;
use Lib\Exceptions\BDDException;
use Lib\Exceptions\RepositoryException;
use Lib\Manager\ExceptionsManager;
use App\Entity\Comments;

class ComRepository extends AbstractEntityRepository
{
    protected static string $table = 'comments';
    protected static string $classMapped = Comments::class;

    public function getComValidByPost($id_post)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = 'SELECT * FROM '.static::$table.' WHERE valid=1 ORDER BY id DESC ';

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

}
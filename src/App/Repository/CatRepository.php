<?php

namespace App\Repository;

use Lib\Abstracts\AbstractEntityRepository;
use App\Entity\Categorie;
use Lib\BDD\BDD;
use Lib\Exceptions\BDDException;

class CatRepository extends AbstractEntityRepository
{
    protected static string $table = 'post';
    protected static string $classMapped = Categorie::class;

    public static function getCat()
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = 'SELECT id,libelle_cat FROM categorie';
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
}
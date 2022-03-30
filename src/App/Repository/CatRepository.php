<?php

namespace App\Repository;

use Lib\Abstracts\AbstractEntityRepository;
use App\Entity\Categorie;
use Lib\BDD\BDD;

class CatRepository extends AbstractEntityRepository
{
    protected static string $table = 'categorie';
    protected static string $classMapped = Categorie::class;

    public static function getCat()
    {
        $query = "SELECT id,libelle_cat FROM ".static::$table;
        $prep = BDD::prepare($query);
        return self::execprepa($prep,true);
    }
}
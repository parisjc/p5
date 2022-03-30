<?php

namespace App\Entity;

use Lib\Abstracts\AbstractEntite;

class Categorie extends AbstractEntite
{
    protected int $id;
    protected string $libelle_cat;

    public function __construct(array $arr=NULL) {
        if($arr!=NULL)
            $this->hydrate($arr);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelleCat(): string
    {
        return $this->libelle_cat;
    }
}
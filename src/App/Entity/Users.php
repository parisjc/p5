<?php

namespace App\Entity;
use Lib\Abstracts\AbstractEntite;

class Users extends AbstractEntite
{
    protected int $id;
    protected string $nom;
    protected string $prenom;
    protected string $username;

    public function __construct(array $arr=NULL) {
        if($arr!=NULL)
            $this->hydrate($arr);
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
<?php

namespace App\Entity;

use Lib\Abstracts\AbstractEntite;

class Comments extends AbstractEntite
{
    protected int $id;
    protected string $nom;
    protected string $prenom;
    protected string $comment;
    protected string $comment_date;
    protected bool $valid;
    protected int $id_post;

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
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getCommentDate(): string
    {
        return $this->comment_date;
    }

    /**
     * @return bool
     */
    public function Valid(): bool
    {
        return $this->valid;
    }


}
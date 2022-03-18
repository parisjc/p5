<?php
namespace App\Entity;

use Lib\Abstracts\AbstractEntite;
use App\Repository\UsersRepository;
use App\Entity\Users;
class Post extends AbstractEntite
{
    protected int $id;
    protected string $title;
    protected string $img;
    protected string $summary;
    protected string $content;
    protected string $creation_date;
    protected Users $id_users;
    protected bool $actif;

    public function __construct(array $arr=NULL) {
        if($arr!=NULL)
            $this->hydrate($arr);
    }

    /**
     * @return Users
     */
    public function getid_users(): Users
    {
        return $this->id_users;
    }

    /**
     * @param int $id_users
     */
    public function setid_users(int $id_users): void
    {
        $usersRep = new UsersRepository();
        $this->id_users = $usersRep::getById($id_users);
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getCreation_Date(): string
    {
        return $this->creation_date;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getActif(): bool
    {
        return $this->actif;
    }


}
<?php

namespace App\Repository;
use App\Entity\Users;
use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;

class UsersRepository extends AbstractEntityRepository
{
    protected static string $table = 'users';
    protected static string $classMapped = Users::class;


    public function ValidLogin($username,$pwd)
    {
        $resq = BDD::query("SELECT users.*,role.libelle_role FROM users,role WHERE users.username = '".$username."' AND role.id = users.id_role");

        $res = self::fetch($resq,false);
        if(!empty($res)) {
            if (password_verify($pwd, $res[0]['mdp'])) {
                $_SESSION['user']['nom']=$res[0]['nom'];
                $_SESSION['user']['prenom']=$res[0]['prenom'];
                $_SESSION['user']['role']=$res[0]['libelle_role'];
                return true;
            } else {
                return false;
            }
        }
        else
        {
            var_dump(!empty($res));
            return false;
        }
    }
}
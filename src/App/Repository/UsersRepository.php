<?php

namespace App\Repository;
use App\Entity\Users;
use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;
use OAuthProvider;
use PDO;

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
                $_SESSION['user']['id']=$res[0]['id'];
                $_SESSION['user']['nom']=$res[0]['nom'];
                $_SESSION['user']['prenom']=$res[0]['prenom'];
                $_SESSION['user']['username']=$res[0]['username'];
                $_SESSION['user']['email']=$res[0]['email'];
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

    public static function setUpdateAtifByUser($id,$actif)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "UPDATE users SET activated = :actif WHERE id=$id";

        $prep = $bdd::prepare($query);
        $prep->bindParam('actif',$actif,PDO::PARAM_BOOL);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            return false;
        }
    }

    public static function setSuppUser($id)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "DELETE FROM users WHERE id=:id";

        $prep = $bdd::prepare($query);
        $prep->bindParam('id',$id,PDO::PARAM_INT);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            return false;
        }
    }

    public static function saveusers($nom,$prenom,$email,$username,$mdp)
    {
        $key = bin2hex(random_bytes(5));
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;

        $query = "INSERT INTO users (nom,prenom,email,username,mdp,validation_key) VALUES (:nom,:prenom,:email,:username,:mdp,:validation_key)";

        $prep = $bdd::prepare($query);
        $password = password_hash($mdp,PASSWORD_DEFAULT);
        $prep->bindParam('nom',$nom,PDO::PARAM_STR);
        $prep->bindParam('prenom',$prenom,PDO::PARAM_STR);
        $prep->bindParam('email',$email,PDO::PARAM_STR);
        $prep->bindParam('username',$username,PDO::PARAM_STR);
        $prep->bindParam('mdp',$password,PDO::PARAM_STR);
        $prep->bindParam('validation_key',$key,PDO::PARAM_STR);

        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return $rqtResult;
        }else{
            return false;
        }
    }
}
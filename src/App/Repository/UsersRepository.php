<?php

namespace App\Repository;
use App\Entity\Users;
use Exception;
use Lib\Abstracts\AbstractEntityRepository;
use Lib\BDD\BDD;
use Lib\Exceptions\BDDException;
use PDO;

class UsersRepository extends AbstractEntityRepository
{
    protected static string $table = 'users';
    protected static string $classMapped = Users::class;


    /**
     * @throws BDDException
     */
    public function ValidLogin($username, $pwd):bool
    {
        $resq = BDD::query("SELECT users.*,role.id AS id_role FROM ".static::$table.",role WHERE users.username = '".$username."' AND role.id = users.id_role");

        $res = self::fetch($resq,false);
        if($res[0]['activated'] == true) {
            if (!empty($res)) {
                if (password_verify($pwd, $res[0]['mdp'])) {
                    $session['user']['id'] = $res[0]['id'];
                    $session['user']['nom'] = $res[0]['nom'];
                    $session['user']['prenom'] = $res[0]['prenom'];
                    $session['user']['username'] = $res[0]['username'];
                    $session['user']['email'] = $res[0]['email'];
                    $session['user']['role'] = $res[0]['id_role'];
                    $_SESSION=$session;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }else {
            return false;
        }
    }

    public static function setUpdateAtifByUser($id,$actif)
    {
        $query = "UPDATE ".static::$table." SET activated = :actif WHERE id=$id";

        $prep = BDD::prepare($query);
        $prep->bindParam('actif',$actif,PDO::PARAM_BOOL);
        return self::execprepa($prep);
    }

    public static function setSuppUser($id)
    {
        $query = "DELETE FROM ".static::$table." WHERE id=:id";

        $prep = BDD::prepare($query);
        $prep->bindParam('id',$id,PDO::PARAM_INT);
        return self::execprepa($prep);
    }

    /**
     * @throws Exception
     */
    public static function saveusers($nom, $prenom, $email, $username, $mdp)
    {
        $key = bin2hex(random_bytes(5));

        $query = "INSERT INTO ".static::$table." (nom,prenom,email,username,mdp,validation_key) VALUES (:nom,:prenom,:email,:username,:mdp,:validation_key)";

        $prep = BDD::prepare($query);
        $password = password_hash($mdp,PASSWORD_DEFAULT);
        $prep->bindParam('nom',$nom,PDO::PARAM_STR);
        $prep->bindParam('prenom',$prenom,PDO::PARAM_STR);
        $prep->bindParam('email',$email,PDO::PARAM_STR);
        $prep->bindParam('username',$username,PDO::PARAM_STR);
        $prep->bindParam('mdp',$password,PDO::PARAM_STR);
        $prep->bindParam('validation_key',$key,PDO::PARAM_STR);

        return self::execprepa($prep);
    }
}
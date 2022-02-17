<?php
namespace Lib\BDD;

use Lib\Exceptions\BDDException;
use Lib\Manager\ExceptionsManager;
use mysqli;
use mysqli_sql_exception;
use PDO;
use PDOException;
use TypeError;

abstract class BDDMain
{
    private static $connexion;
    private static $connexionError;
    private static bool $connected = false;

    protected static function connect($data){
        self::$connexionError = null;
        $typedb = $data['type'];
        try{
            self::$connexion = new PDO($data['bdd'].':host='.$data['host'].';port='.$data['port'].';dbname='.$data['database'].';', $data['user'], $data['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }catch(TypeError $e) {
            self::$connexionError = "Une erreur s'est produite lors de l'instanciation de la base de données.";
            ExceptionsManager::addException(new BDDException(self::$connexionError));
            return false;
        }catch(mysqli_sql_exception $e){
            self::$connexionError = "Une erreur s'est produite sur la base de données '".$data['database']."'<br>".utf8_encode($e->getMessage());
            ExceptionsManager::addException(new BDDException(self::$connexionError));
            return false;
        }catch(PDOException $e){
            self::$connexionError = "Une erreur s'est produite sur la base de données '".$data['database']."'<br>".utf8_encode($e->getMessage());
            ExceptionsManager::addException(new BDDException(self::$connexionError));
            return false;
        }
        self::get();
        return true;
    }

    public static function init($login)
    {
        if($login['host'] != '') {
            $class = get_called_class();
            new $class($login);
        }
    }

    public static function get()
    {
        if(self::$connexionError == null){
            if(self::getType() == "mysqli") {
                self::$connexion->set_charset("utf8");
            }
            get_called_class()::setDB(self::$connexion);
            self::$connected = true;
            return self::$connexion;
        }
    }

    public static function createBuilder($type){
        return new BDDBuilder($type);
    }

    protected static function setDB($db){
        if(get_class($db) != "PDO" && get_class($db) != "mysqli"){
            ExceptionsManager::addException(new BDDException('Le type de BDD est incorrect (got \''.get_class($db).'\')'));
            return;
        }
        get_called_class()::$db = $db;
    }

    public static function getType(){
        if(!isset(get_called_class()::$db)){
            return get_class(self::$connexion);
        }else{
            return get_class(get_called_class()::$db);
        }
    }

    public static function getDB(){
        return get_called_class()::$db;
    }

    private static function ping(){
        $dbClass = get_called_class()::$db;
        switch(get_class($dbClass)){
            case 'mysqli':
                return !$dbClass->ping();
            case 'PDO':
                try {
                    $dbClass->query('SELECT 1');
                    return true;
                } catch (PDOException $e) {
                    return false;
                }
            default:
                throw new BDDException("Le type de connexion à la base de données est incorrect.");
        }
    }

    public static function query($req){
        $dbClass = get_called_class()::$db;
        if(!isset($dbClass)){
            return false;
        }
        if(!self::ping()){
            self::connect(get_called_class()::$con);
        }
        try {
            $res = $dbClass->query($req);
        } catch (PDOException $e) {
            ExceptionsManager::addException(new BDDException($e->getMessage()));
            return false;
        }
        if(!$res){
            switch(get_class($dbClass)){
                case 'mysqli':
                    ExceptionsManager::addException(new BDDException($dbClass->error));
                case 'PDO':
                    ExceptionsManager::addException(new BDDException($res->errorInfo()[2]));
                default:
                    throw new BDDException("Le type de connexion à la base de données est incorrect.");
            }
        }
        return $res;
    }

    public static function prepare($sql)
    {
        if(!self::$connected)
        {
            return false;
        }
        return self::getDB()->prepare($sql);
    }

    public static function lastInsert(){
        $dbClass = get_called_class()::$db;
        switch(get_class($dbClass)){
            case 'mysqli':
                return $dbClass->insert_id;
            case 'PDO':
                return $dbClass->lastInsertId();
            default:
                throw new BDDException("Le type de connexion à la base de données est incorrect.");
        }
    }
}
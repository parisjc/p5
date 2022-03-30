<?php
namespace Lib\Abstracts;

use Lib\BDD\BDD;
use Lib\Exceptions\BDDException;
use Lib\Exceptions\NotFoundException;
use Lib\Exceptions\RepositoryException;
use Lib\Manager\ExceptionsManager;
use ReflectionClass;
use ReflectionException;

/**
 * AbstractEntityRepository
 */
abstract class AbstractEntityRepository {

    private static function convertDataFormat(array $class){
        $mapping = array();
        try {
            $oReflectionClass = new ReflectionClass(static::$classMapped);
            $props = $oReflectionClass->getProperties();
            foreach($props as $prop){
                foreach($class as $k => $v){
                    if(strtolower($k) == strtolower($prop->getName())){
                        $mapping[$prop->getName()] = $v;
                        break;
                    }
                }
            }
        } catch (ReflectionException $e) {}
        return $mapping;
    }

    protected static function fetch($result, $obj=true){
        if($result == false){
            return false;
        }
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;
        $resultSet = array();
        $cla = $bdd::getType();
        switch ($cla) {
            case 'mysqli':
                while($row = $result->fetch_assoc())
                {
                    $resultSet[] = $obj ? new static::$classMapped(self::convertDataFormat($row)) : $row;
                }
                break;
            case 'PDO':
                while($row = $result->fetch(\PDO::FETCH_ASSOC))
                {
                    $resultSet[] = $obj ? new static::$classMapped(self::convertDataFormat($row)) : $row;
                }
                break;
            default:
                ExceptionsManager::addException(new RepositoryException('Une erreur est survenue dans AbstractEntityRepository::fetch()'));
                break;
        }
        return $resultSet;
    }

    public static function getAll($obj=true)
    {
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;
        $cla = $bdd::getType();


        $query = 'SELECT * FROM '.static::$table;

        $prep = $bdd::prepare($query);
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return self::fetch($prep,$obj);
        }else{
            ExceptionsManager::addException(new BDDException($bdd::getDB()->errorInfo()[2]));
        }
        return false;
    }

    public static function getById($id)
    {
        if(!isset($id)){
            ExceptionsManager::addException(new RepositoryException('Missing parameters in the repository '.__CLASS__.' for the function '.__FUNCTION__));
            return new static::$classMapped();
        }
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;
        $resultSet = NULL;
        $cla = $bdd::getType();
        switch ($cla) {
            case 'mysqli':
                $query = 'SELECT * FROM '.static::$table .
                        ' WHERE id = :id';
                $reqPrep = $bdd::prepare($query);
                $rqtResult = $reqPrep->execute(array('id' => $id));
                if($rqtResult !== false)
                {
                    $res = $reqPrep->get_result();
                    $row = $res->fetch_assoc();
                    if($row)
                    $resultSet = new static::$classMapped($row);
                }else{
                    ExceptionsManager::addException(new BDDException($bdd::getDB()->error));
                }
                break;
            case 'PDO':
                $query = 'SELECT * FROM '.static::$table .
                        ' WHERE id = :id';
                $reqPrep = $bdd::prepare($query);
                $rqtResult = $reqPrep->execute(array('id' => $id));
                if($rqtResult !== false) {
                    while ($row = $reqPrep->fetch(\PDO::FETCH_ASSOC)) {
                        $resultSet = new static::$classMapped(self::convertDataFormat($row));
                    }
                    break;
                }else{
                    ExceptionsManager::addException(new BDDException($bdd::getDB()->errorInfo()[2]));
                }
            default:
                ExceptionsManager::addException(new RepositoryException('Type de base de données non reconnu.'));
                break;
        }
        return $resultSet;
    }

    public static function getBy(array $arr)
    {
        if(count($arr) > 0)
        {
            $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;
            $resultSet = NULL;
            $cla = $bdd::getType();

            $query = "SELECT * FROM ".static::$table." WHERE ";
            foreach (array_keys($arr) as $param)
            {
                $query .= ':'.$param;
            }
            $reqPrep = $bdd::prepare($query);
            $rqtResult = $reqPrep->execute($arr);
            switch ($cla) {
                case 'mysqli':
                    if($rqtResult !== false)
                    {
                        $res = $reqPrep->get_result();
                        $row = $res->fetch_assoc();
                        if($row)
                            $resultSet = new static::$classMapped($row);
                    }else{
                        ExceptionsManager::addException(new BDDException($bdd::getDB()->error));
                    }
                    break;
                case 'PDO':
                    if($rqtResult !== false) {
                        while ($row = $reqPrep->fetch(\PDO::FETCH_ASSOC)) {
                            $resultSet = new static::$classMapped(self::convertDataFormat($row));
                        }
                        break;
                    }else{
                        ExceptionsManager::addException(new BDDException($bdd::getDB()->errorInfo()[2]));
                    }
                default:
                    ExceptionsManager::addException(new RepositoryException('Type de base de données non reconnu.'));
                    break;
            }
            return $resultSet;
        }else{
            return self::getAll();
        }
    }

    public static function sauver(AbstractEntite $entity){
        return self::action($entity, 'save');
    }

    public static function update(AbstractEntite $entity){
        return self::action($entity, 'update');
    }

    private static function action(AbstractEntite $entity, $type)
    {
        if(!isset($type) || !isset($entity)){
            ExceptionsManager::addException(new RepositoryException('Missing parameters in the repository '.__CLASS__.' for the function '.__FUNCTION__));
            return false;
        }
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;
        $idField = 'get'.ucfirst(static::$idFieldName);
        if($type=="update" && $entity->$idField() == null){
            ExceptionsManager::addException(new RepositoryException('Invalid id entity in the repository '.__CLASS__.' for the function '.__FUNCTION__.' for update'));
            return false;
        }
        $build = $bdd::createBuilder($type);
        try {
            $reflection = new ReflectionClass($entity);
            $build->setTable($reflection->getShortName());
            $properties = $reflection->getDefaultProperties();
            if($type=="update") {
                $row = $build;
            }else{
                $row = $build->addrow();
            }

            foreach ($properties as $property => $null){
                $getter = 'get'.ucfirst($property);
                $val =  $entity->$getter();
                if($property == static::$idFieldName && $type == "update"){
                    $row->setId(static::$idFieldName, $val);
                    continue;
                }
                if($val != null){
                    $row->addValue($property, $val);
                }
            }
            $res = $build->exec();
            if($res != false){
                return true;
            }else{
                return false;
            }
        } catch (ReflectionException $e) {
            ExceptionsManager::addException(new NotFoundException("La classe '$e' n'est pas valide ou introuvable."));
        }
        return false;
    }

    public static function delete($id) {
        if(!isset($id)){
            ExceptionsManager::addException(new RepositoryException('Missing parameters in the repository '.__CLASS__.' for the function '.__FUNCTION__));
            return false;
        }
        $bdd = isset(static::$classBDD)?static::$classBDD:BDD::class;
        return $bdd::query("DELETE FROM ".static::$table." WHERE ".static::$idFieldName."=$id");
    }

    public static function execprepa($prep,$fetch=false)
    {
        $rqtResult = false;
        if($prep !== false)
        {
            $rqtResult = $prep->execute();
        }

        if($rqtResult)
        {
            return !$fetch?$rqtResult:self::fetch($prep);
        }else{
            return false;
        }
    }
}

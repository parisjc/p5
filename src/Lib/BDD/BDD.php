<?php
namespace Lib\BDD;

class BDD extends BDDMain
{
    protected static $db;
    protected static array $con;

    public function __construct(array $login=null)
    {
        if(isset($login) && $login['host'] != ''){
            self::connect($login);
            self::$con = $login;
        }
    }

    public static function getDb(){
        return self::$db;
    }
}
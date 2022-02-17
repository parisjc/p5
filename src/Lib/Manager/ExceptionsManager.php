<?php
namespace Lib\Manager;

use Lib\Autoload;
use Lib\Controller\ExceptionsController;
use Lib\Exceptions\NotFoundException;
use ReflectionClass;

class ExceptionsManager
{
    private static array $ExceptionList = array();
    private static $adminCheck = null;

    public static function showErrorPage(){
        $defaultCtr = new ExceptionsController();
        $defaultCtr->DefaultAction();
    }

    public static function checkError() {
        $exceptions = self::getExceptions();
        if(count($exceptions) > 0) {
            $content = "";
            foreach ($exceptions as $errorType => $error) {
                $content .= '<tr class="table-header">'
                    . '<th colspan="2">' . $errorType . '</th>'
                    . '</tr>';
                foreach ($error as $err) {
                    $content .= '<tr>'
                        . '<td>' . $err['message'] . '</td>'
                        . '<td><i class="fas fa-angle-down stacktrace-toggle"></i></td>'
                        . '</tr>'
                        . '<tr class="exception-stacktrace">'
                        . '<td colspan="2">' . str_replace("\\", "\\\\", $err['stacktrace']) . '</td>'
                        . '</tr>';
                }
                $content .= '<tr class="exception-separator"><td colspan="2"></td></tr>';
            }
            $script = "<script>";
            $script .= "$('.exception-container').removeClass('exception-hide').find('table').html(\"" . str_replace('"', '\"', $content) . "\");";
            $script .= "</script>";
            echo $script;
        }
    }

    public static function addException($e){
        if (!$e instanceof \Exception && !$e instanceof \Error) {
            self::addException(new \InvalidArgumentException('The exception must be a Exception or a Error object.'));
        }
        try {
            $reflect = new ReflectionClass($e);
            $class = $reflect->getShortName();
            $file = $e->getFile().':'.$e->getLine();
            if(!isset(self::$ExceptionList[$class])){
                self::$ExceptionList[$class] = array();
            }
            self::$ExceptionList[$class][] = array(
                'message' => $e->getMessage(),
                'stacktrace' => '<b>'.$file.'</b><br>'.str_replace("\n", '<br>', $e->getTraceAsString())
            );
        } catch (\ReflectionException $e) {
            self::addException(new NotFoundException("La classe '$e' n'est pas valide ou introuvable."));
        }

        $lsDir = Autoload::getRootPath()."var/logs" ;
        if (!file_exists($lsDir)) {
            mkdir($lsDir, 0777, true);
        }
        $file = $lsDir."/project_errors.log" ;
        $file = fopen($file,"a+");

        $time = date('d-m-Y H:i:s');
        $data = "[$time] [ERROR] : ".$e->getMessage()."\n\t".str_replace("\n", "\n\t", $e->getTraceAsString())."\n";
        fwrite($file, $data);
        fclose($file);
    }

    public static function getIndex(){
        $index = array();
        foreach (self::$ExceptionList as $exception => $value){
            $index[] = $exception;
        }
        return $index;
    }

    public static function getValuesByIndex($index){
        return self::$ExceptionList[$index];
    }

    public static function getExceptions(){
        return self::$ExceptionList;
    }

    public static function setAdminCheck($check){
        if(is_callable($check)){
            self::$adminCheck = $check;
        }
    }

    public static function checkAdmin(){
        if(is_callable(self::$adminCheck)) {
            return self::$adminCheck->__invoke();
        }else{
            return true;
        }
    }
}
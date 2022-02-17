<?php

namespace Lib;

class Autoload
{
    public static function getHost(){
        return '../'.$_SERVER['HTTP_HOST'] ?: '';
    }

    public static function getRootPath()
    {
        $caller = array_filter(get_included_files(), function($index) {
            return strpos($index, 'index.php') !== false;
        });
        return count($caller) == 0 ? './' : './../';
    }

    private static function getSubDirectories($dir)
    {
        $subDir = array();
        $directories = array_filter(glob($dir), 'is_dir');
        $subDir = array_merge($subDir, $directories);
        foreach ($directories as $directory) $subDir = array_merge($subDir, self::getSubDirectories($directory.'/*'));
        return $subDir;
    }

    public static function init()
    {
        spl_autoload_register(function ($className) {
            $classNameWithPath = explode('\\', $className);
            $className = end($classNameWithPath);
            foreach (self::getSubDirectories(self::getRootPath().'src') as $base) {
                $fullName = $base . '/' . $className . '.php';
                if (file_exists($fullName)) {
                    require_once($fullName);
                    return true;
                }
            }
            return false;
        });
    }
}
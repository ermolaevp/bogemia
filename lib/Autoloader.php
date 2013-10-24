<?php
class Autoloader
{
    private static $_lastLoadedFilename;
 
    public static function loadPackages($className)
    {   
        $className = __DIR__ . '/../' . '\\' . $className;
        $pathParts = explode('\\', $className);
        self::$_lastLoadedFilename = implode(DIRECTORY_SEPARATOR, $pathParts) . '.php';
        
        if(file_exists(self::$_lastLoadedFilename))
            require_once(self::$_lastLoadedFilename);
        else {
            //var_dump(debug_backtrace());
            //die(sprintf("File %s is not exists.", self::$_lastLoadedFilename));
        }
    }
 
    public static function loadPackagesAndLog($className)
    {
        self::loadPackages($className);
        printf("<p>Class %s was loaded from %s</p>\n", $className, self::$_lastLoadedFilename);
    }
}
spl_autoload_register(array('Autoloader', 'loadPackages'));

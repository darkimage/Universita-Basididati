<?php
if(!defined('ROOT'))
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);

require_once(ROOT."/private/session.php");
require_once(ROOT."/private/dbConnection.php");

class Services {
    private static $instance;
    private function __construct() {}

    public static function getInstance()
    {
        if ( !isset(self::$instance))
        {
                self::$instance = new self;
        }
        
        self::$instance->loadServices();
        
        return self::$instance;
    }

    private function loadServices(){
        foreach (scandir(ROOT.'/private/services') as $filename) {
            $path = ROOT.'/private/services/'.$filename;
            if (is_file($path)) {
                require_once($path);
                $found = preg_match_all("/^(.+)Service.php$/",$filename,$matches);
                if($found){
                    if(!isset($this->{$matches[1][0]})){
                        $class = $matches[1][0];
                        $this->{$matches[1][0]} = new $class();
                    }
                }
            }
        }
    }
}

?>
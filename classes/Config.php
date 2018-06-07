<?php
class Config{
    public static function get($path=null){
        if($path){
            $config = $GLOBALS['config'];
            $path = explode('/', $path);

            foreach($path as $part) {
                if (isset($config[$part])) {
                    $config = $config[$part];
                }
            }
            return $config;
        }
        return false;
    }
}

//TO DO: look up explode
//check if things exist to avoid security issues with Config::get()
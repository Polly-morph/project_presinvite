<?php 
session_start();

$GLOBALS['config']=array(
    'mysql' => array(
        'host' => 'mysql.cms.gre.ac.uk',
        'username' =>'sp116',
        'password' =>'sp116mysql',
        'db' => 'mdb_sp116'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name'=>'token'
    )
);
//runs every time the class is accessed, instead of using require for every file and aid managing class if file names change
spl_autoload_register(function($class){
    require_once 'classes/' .$class. '.php';
});

require_once 'functions/sanitise.php';
?>
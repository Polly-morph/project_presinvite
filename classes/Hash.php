<?php
class Hash{
    public static function make($string, $salt=''){
        return hash('sha256', $string . $salt);
    }
    //salt is a security measurement
    //adds randomly generated secure string to the end of a password
    //can be used to validate the existing hash password
    //prevent looking up passwords easily if the hash is uncovered
    public static function salt($length){
        return mcrypt_create_iv($length);
    }
    public static function unique(){
        return self::make(uniqid());
    }
}
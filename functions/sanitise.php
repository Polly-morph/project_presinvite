<?php 
function escape($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}
/*TO DO:
RESEARCH HTML ENTITIES ESCAPE SECURITY FOR MYSQL*/
?>
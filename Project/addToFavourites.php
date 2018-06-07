<?php
require_once 'core/init.php';

//Angular only routes to this page if there is php code included as the file is saved as *.php

date_default_timezone_set('GMT');
$pres = new Presentation();
$user = new User();
$loggedIn_used_id = escape($user->data()->user_id);
$favourite = new Favourite();
$pres_id = (int)$_GET['pres_id'];
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
if ($pres->find($pres_id)) {
    try {
        $favourite->create(array(
            'user_id' => $loggedIn_used_id,
            'pres_id' => $pres_id,
            'dateAdded' => date('Y-m-d H:i:s'),
            'active' => 1
        ));
//        $pres_title=escape($pres->data()->title);
//        Session::flash('addedToFavourites','Presentation "'.$pres_title.'" has been added to your favourites.');
        echo '<script>history.back();</script>';
    } catch (PDOException $e) {
        //fix exception catch method
        die($e->getMessage());
    }
} else {
    echo 'There was an issue adding this presentation to your favourites.';
}
?>
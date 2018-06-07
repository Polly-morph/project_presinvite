<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
if ($user->exists()) {

    $favourite = new Favourite();
    $fav_id = (int)$_GET['fav_id'];
    if ($fav_id) {
        $favourite->deleteFavourite($fav_id, 0, 0);
        echo '<script>history.back();</script>';
    } else {
        echo 'There was an issue removing this presentation from your favourites.';
    }
}
?>
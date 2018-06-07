<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');

} else {
    $first_name = escape($user->data()->first_name);
    $last_name = escape($user->data()->last_name);

    if ($user->hasPermission('admin')) {
        echo $first_name . ' ' . $last_name . ' is an ADMIN';
    } elseif ($user->hasPermission('speaker')) {
        echo $first_name . ' ' . $last_name . ' is a SPEAKER';
    } elseif ($user->hasPermission('organiser')) {
        echo $first_name . ' ' . $last_name . ' is an ORGANISER';
    } else {
        echo $first_name . ' ' . $last_name . ' has NO PERMISSIONS to view the site';
    }
} ?>
<?php
require_once 'core/init.php';

//if(Session::exists('home')){
//    echo '<p>'.Session::flash('home').'</p>';
//}
$user = new User();
$user_location = escape($user->data()->location);
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
if ($user->exists()) {
if ($user_location == null || $user_location == '') {
    Redirect::to('profile.php');
} elseif ($user->hasPermission('speaker')) {
    Redirect::to('myPresentations.php');
} elseif ($user->hasPermission('organiser')) {
    Redirect::to('browse.php');
} else {
    Redirect::to('profile.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PresInvite</title>
    <meta charset="UTF-8">
    <meta name="description" content="Present your ideas, connect with events">
    <meta name="keywords" content="presentations,slides, PresInvite, invite, speaker, event, organiser, development,
    design, software, web technologies">
    <meta name="author" content="Polina Aleksandrova Stoyanova">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--Icon-->
    <meta name="msapplication-TileColor" content="#ff7043">
    <meta name="msapplicationTileImage" content="images/presInviteIconTile.png">
    <link rel="apple-touch-icon-precomposed" href="images/presInvite_icon.png">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

    <!--Custom CSS-->
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>

    <!--    Flaticon font    -->
    <link rel="stylesheet" type="text/css" href="font/flaticon/flaticon.css">

    <!--    Materialize CSS -->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>
    <!--            jQuery required by MaterializeCSS-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <!-- Include JavaScript files required by Materialize -->
    <script type="text/javascript" src="js/materialize.min.js"></script>

    <!--AngularJS -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.0-beta.5/angular.min.js"></script>

    <!--        MaterializeCSS mobile menu-->
    <script>
        $(document).ready(function () {
            $(".button-collapse").sideNav();
        });
    </script>
</head>

<body ng-app="PresentationApp" class="grey lighten-4">
<?
if ($user->isLoggedIn()) {
    $user_id = escape($user->data()->user_id);
    $email = escape($user->data()->email);
    $user_first_name = escape($user->data()->first_name);
    $user_profile_image = escape($user->data()->profile_image);
    if ($user_profile_image != '' && $user_profile_image != null) {
        $user_profile_image = '<img class="center-align circle" src="' . $user_profile_image . '" alt="profile
                        image">';
    } else {
        $user_profile_image = '<i class="align-left mdi-action-account-circle profilePic"></i>';
    }
    ?>
    <nav class="deep-orange lighten-1">
        <div class="nav-wrapper">
            <div class="col s12">
                <a href="#" data-activates="mobile-demo" class="button-collapse"><i
                        class="mdi-navigation-menu"></i></a>

                <div class="logo align-left logoIndent">PresInvite</div>
                <ul class="hide-on-med-and-down right">
                    <li><a href='browse.php'>Browse</a></li>
                    <li><a href="myPresentations.php">Create or Edit</a></li>
                    <li><a href="favourites.php">Favourites</a></li>
                    <li class="valign-wrapper">
                        <div class="valign">
                            <a href="profile.php"><? echo $user_first_name; ?></a>
                        </div><? echo $user_profile_image; ?></li>
                    <li class="col m1 main center-align">
                        <a class="" href="logout.php">
                            <img style="height:80%; width:80%; vertical-align: middle;"
                                 src="font/material-design-icons/logout.png" alt="logout">
                        </a>
                    </li>
                </ul>
                <ul class="side-nav hide-on-large-only" id="mobile-demo">
                    <li>
                        <a href="profile.php">
                            <span><? echo $user_profile_image; ?></span>
                            <span><? echo $user_first_name; ?></span>
                        </a>

                        <div class="divider"></div>
                    </li>
                    <li><a href='browse.php'>Browse</a></li>
                    <li><a href="myPresentations.php">Create or Edit</a></li>
                    <li><a href="favourites.php">Favourites</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <footer class="page-footer center-align deep-orange lighten-1">
        <div class="col s6 m6 offset-m3" style="padding-bottom: 22px; font-size:11pt;">
            <a class="grey-text text-lighten-3" href="terms.php">Terms of Use</a>
            <span class="white-text"> | </span>
            <a class="grey-text text-lighten-3" href="privacy.php">Privacy Policy</a>
        </div>
        <div class="white-text">&copy; 2015 Polina Stoyanova. All Rights Reserved.</div>
    </footer>
<?php }
} ?>
</body>
</html>

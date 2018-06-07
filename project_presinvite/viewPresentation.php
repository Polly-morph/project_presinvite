<?php
require_once 'core/init.php';

//Angular only routes to this page if there is php code included as the file is saved as *.php
$pres = new Presentation();
$currentUser = new User();
$user_id = escape($currentUser->data()->user_id);
$user = new User();
$pres_id = (int)$_GET['pres_id'];
$userFavourites = new Favourite();
$presTag = new Presentation();

if (!$currentUser->isLoggedIn()) {
    Redirect::to('login.php');
}
if ($currentUser->exists()) {
if ($pres->find($pres_id)) {
    try {
        $contents = base64_decode(escape($pres->data()->contents));
        $title = escape($pres->data()->title);
        $description = escape($pres->data()->description);
        $url = escape($pres->data()->url);
        $pres_user_id = escape($pres->data()->user_id);
        $pres_tag_id = escape($pres->data()->tags);

        if ($user->find($pres_user_id)) {
            $first_name = escape($user->data()->first_name);
            $last_name = escape($user->data()->last_name);
            $profile_image = escape($user->data()->profile_image);
            $occupation = escape($user->data()->occupation);
            $company = escape($user->data()->company);
            if ($company !== '' && $company != null) {
                $company = 'at ' . $company;
            }
            $location = escape($user->data()->location);
            $social_media_text = escape($user->data()->social_media_text);
            $social_media_link = escape($user->data()->social_media_link);
            $email = escape($user->data()->email);


            $userFavourites->isFavourite($user_id, $pres_id);
            $fav_id = escape($userFavourites->getCurrentId());

            if ($userFavourites->checkFavourite()) {
                $isFav = '<a href="removeFromFavourites.php?fav_id=' . $fav_id . '"><i
                        class="mdi-action-star-rate medium amber-text text-darken-1"></i></a>';
            } else {
                $isFav = '<a href="addToFavourites.php?pres_id=' . $pres_id . '"><i class="mdi-action-star-rate medium
                grey-text text-lighten-2"></i></a>';
            }
        }
        if ($presTag->getTagNames($pres_tag_id)) {
            $pres_tag_name = escape($presTag->data()->tag_name);
            $pres_tag_colour = escape($presTag->data()->tag_colour);
        }

    } catch (PDOException $e) {
        //fix exception catch method
        die($e->getMessage());
    }
} else {
    echo 'There was an issue viewing this presentation.';
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

    <!--    Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>

    <!--    Materialize CSS -->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>
    <!--            jQuery required by MaterializeCSS-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <!-- Include JavaScript files required by Materialize -->
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".button-collapse").sideNav();
        });
    </script>
</head>

<body ng-app="PresentationApp" class="grey lighten-4">
<!--        Check if user is logged in and display page contents-->
<?
if ($currentUser->isLoggedIn()) {
    $user_first_name = escape($currentUser->data()->first_name);
    $user_profile_image = escape($currentUser->data()->profile_image);
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
    <main>
        <div class="row">
            <div class="col s12 m4 l3 white" style="height:100%;">
                <?
                echo '<div class="indent">
                        <div class="align-right" style="margin-top:-20px;">' . $isFav . '</div>
                        <h3 id="presTitle">' . $title . '</h3>
                        <div style="clear: both;">' . $description . '</div>

                        <div class="divider" style="margin-top:20px;margin-bottom:20px;"></div>
                        <tag class="preview ' . $pres_tag_colour . ' white-text">#' . $pres_tag_name . '</tag>
                        <img class="circle presProfilePic" src="' . $profile_image . '"/>
                        <div class="name grey-text text-darken-2">' . $first_name . ' ' . $last_name . '</div>
                        <div class="occupation grey-text text-darken-2">' . $occupation . ' ' . $company . '</div>
                        <div class="occupation grey-text">' . $location . '</div>
                        <div class="occupation grey-text">
                            <a href="' . $social_media_link . '"> ' . $social_media_text . '</a>
                        </div>
                        <br>
                        <a style="clear:both; float:left" href="inviteSpeaker.php?pres_id=' . $pres_id . '"
                        class="position-bottom white-text btn-flat btn-medium green waves-effect">Invite
                        </a>
                    </div>';
                ?>
            </div>
            <div class="col s12 m8 l9">
                <?
                if ($contents !== '' && $contents !== null) {
                    echo '<iframe width="600" height="520" frameBorder="0" webkitAllowFullScreen mozAllowFullscreen
                    allowfullscreen class="col s12" src="slides.php?pres_id=' . $pres_id .
                        '"></iframe>';
                } elseif ($url !== '' && $url !== null) {
                    echo '<iframe width="600" height="520" frameBorder="0" webkitAllowFullScreen mozAllowFullscreen
                    allowfullscreen class="col s12" src="' . $url . '"></iframe>';
                }
                ?>
            </div>
        </div>
    </main>
    <footer class="page-footer center-align deep-orange lighten-1">
        <div class="col s6 m6 offset-m3" style="padding-bottom: 22px; font-size:11pt;">
            <a class="grey-text text-lighten-3" href="terms.php">Terms of Use</a>
            <span class="white-text"> | </span>
            <a class="grey-text text-lighten-3" href="privacy.php">Privacy Policy</a>
        </div>
        <div class="white-text">&copy; 2015 Polina Stoyanova. All Rights Reserved.</div>
    </footer>
    <!--            If user is not logged in prompt them to login or register before proceeding-->
<?php }
} ?>

</body>
</html>

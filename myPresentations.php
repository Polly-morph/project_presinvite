<?php
require_once 'core/init.php';

//Angular only routes to this page if there is php code included as the file is saved as *.php
$pres = new Presentation();
$user = new User();
$favourite = new Favourite();
$presTag = new Presentation();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
if ($user->exists()) {
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

<body class="grey lighten-4">
<!--        Check if user is logged in and display page contents-->
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
                                 src="font/material-design-icons/logout.png"
                                 alt="logout">
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
        <h4 class="center-align">My Presentations</h4>

        <div class="center-align">
            <button class="btn-flat btn-medium waves-effect waves-light blue">
                <a href="editPresURL.php" class="white-text">
                    <i class="mdi-content-link small"></i><span class="valign-top"> Add from URL</span>
                </a>
            </button>
            <button class="btn-flat btn-medium waves-effect waves-light green">
                <a href="editPres.php" class="white-text">
                    <i class="mdi-content-add small"></i><span class="valign-top"> Create</span>
                </a>
            </button>
        </div>
        <div class="row">
            <?
            $userFavourites = new Favourite();
            $userPresentations = $pres->getAllUserPresentations($user_id);
            if (!count($userPresentations)) {
                echo '<div class="center-align grey-text">Create your next inspiring presentation to share.</div>';
            }
            foreach ($userPresentations as $row) {
                $pres_id = $row['id'];
                $active = $row['active'];
                if ($pres->find($pres_id)) {
                    try {
                        $contents = base64_decode(escape($pres->data()->contents));
                        $pres_title = escape($pres->data()->title);
                        $pres_desc = escape($pres->data()->description);
                        if ($pres_desc == null || $pres_desc == '') {
                            $pres_desc = 'Please add a short description of your presentation.';
                        }
                        $pres_tag_id = escape($pres->data()->tags);
                        $url = escape($pres->data()->url);
                        $pres_user_id = escape($pres->data()->user_id);
                        if ($presTag->getTagNames($pres_tag_id)) {
                            $pres_tag_name = escape($presTag->data()->tag_name);
                            $pres_tag_colour = escape($presTag->data()->tag_colour);
                        }
                        if ($user->find($pres_user_id)) {
                            $author_user_name = escape($user->data()->user_name);
                            $author_first_name = escape($user->data()->first_name);
                            $author_last_name = escape($user->data()->last_name);
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
                        }
                        echo '<div class="card col s10 offset-s1 l5 cardPadding editCard">
                                    <div class="">
                                        <h5>
                                            <a class="red-text text-accent-2"  href="viewPresentation.php?pres_id=' . $pres_id . '">' . $pres_title . '</a>
                                        </h5>
                                        <div class="position-bottom">
                                            <a href="editPres.php?pres_id=' . $pres_id . '">
                                            <button class="btn-flat waves-effect white-text grey">
                                                    Edit
                                            </button></a>
                                        </div>
                                        <div class="grey-text">' . $pres_desc . '</div>
                                    </div>
                                    <div class="left-align">
                                        <tag class="' . $pres_tag_colour . ' lighten-1 white-text">#' . $pres_tag_name . '</tag>
                                    </div>

                                </div>';
                    } catch (PDOException $e) {
                        //fix exception catch method
                        die($e->getMessage());
                    }
                }
            }
            ?>
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
<?php }
} ?>
</body>
</html>

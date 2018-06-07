<?php
require_once 'core/init.php';

//Angular only routes to this page if there is php code included as the file is saved as *.php
$pres = new Presentation();
$user = new User();
$pres_id = (int)$_GET['pres_id'];
$user_id = escape($user->data()->user_id);
$presTags = new Presentation();
$presExists = false;
$canEdit = false;
$createNew = false;

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
if ($user->exists()) {
if ($user->find($user_id)) {
    $first_name = escape($user->data()->first_name);
    $last_name = escape($user->data()->last_name);
    $profile_image = escape($user->data()->profile_image);
    $occupation = escape($user->data()->occupation);
    $company = escape($user->data()->company);
    if ($company !== '' && $company != null) {
        $company = 'at ' . $company;
    }
    $location = escape($user->data()->location);
    $social_media = escape($user->data()->social_media_id);
    $email = escape($user->data()->email);
}

if ($pres->find($pres_id)) {
    try {
        $presExists = true;

        $contents = base64_decode(escape($pres->data()->contents));
        $title = escape($pres->data()->title);
        $description = escape($pres->data()->description);
        $url = escape($pres->data()->url);
        $pres_user_id = escape($pres->data()->user_id);
        $pres_tag_id = escape($pres->data()->tags);

        if ($presTags->getTagNames($pres_tag_id)) {
            $pres_tag_name = escape($presTags->data()->tag_name);
            $pres_tag_colour = escape($presTags->data()->tag_colour);
        }

    } catch (PDOException $e) {
        //fix exception catch method
        die($e->getMessage());
    }
}
if (!$presExists) {
    $canEdit = true;
    $createNew = true;
    $contents = '<link href="css/impress-demo.css" rel="stylesheet">
    <link href="css/impressNew.css" rel="stylesheet">
    <div id="impress" style="width:50%;height:50px;" class="frameScale"></div>
    <script> if ("ontouchstart" in document.documentElement) {
    document.querySelector(".hint").innerHTML = "<p>Tap on the left or right to navigate</p>";
    } </script>
    <script src="js/impress.js"></script>
    <script>impress().init();</script>';
} elseif ($presExists) {
    if ($user_id == $pres_user_id) {
        $canEdit = true;
    } elseif (!$user_id == $pres_user_id) {
        $canEdit = false;
    }
}
if ($canEdit == false) {
    Redirect::to('myPresentations.php');

}
if ($canEdit) {
    if (Input::exists()) {
        $presentation_title = htmlentities(Input::get('title'), ENT_COMPAT, 'UTF-8');
        $presentation_body = base64_encode(Input::get('presBody'));
        $presentation_desc = htmlentities(Input::get('description'), ENT_COMPAT, 'UTF-8');
        $presentation_tags = escape(Input::get('tag'));
        switch ($presentation_tags) {
            case 'UX':
                $presentation_tag_id = 1;
                break;
            case 'HTML5':
                $presentation_tag_id = 2;
                break;
            case 'JavaScript':
                $presentation_tag_id = 3;
                break;
            default:
                $presentation_tag_id = 4;
                break;
        }
        $url = Input::get('url');

        if ($createNew) {
            $pres->create(array(
                'title' => $presentation_title,
                'contents' => $presentation_body,
                'description' => $presentation_desc,
                'url' => $url,
                'user_id' => $user_id,
                'tags' => $presentation_tag_id,
                'active' => 1
            ));
        } else {
            $pres->update($pres_id, array(
                'title' => $presentation_title,
                'description' => $presentation_desc,
                'contents' => $presentation_body,
                'url' => $url,
                'tags' => $presentation_tag_id
            ));
        }
        Session::flash('presentationSaved', 'Your presentation has been saved.');
        Redirect::to($_SERVER['REQUEST_URI']);
    }
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

    <!--    Custom CSS-->
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
    <!--    ngWYSIWYG editor   -->
    <link rel="stylesheet" href="css/editor.css">
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
    <!--    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.5/angular-route.min.js"></script>
    <!-- load assets -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.0-beta.5/angular-sanitize.js"></script>
    <script src="js/wysiwyg.js"></script>
    <!-- Rangy CSS Class Applier helper for contentEditable -->
    <script src="js/rangy-core.js"></script>
    <script src="js/rangy-cssclassapplier.js"></script>
    <script src="js/rangy-selectionsaverestore.js"></script>
    <script>
        $(document).ready(function () {
            $(".button-collapse").sideNav();
        });
    </script>
    <script>
        angular.module('PresentationApp', []).controller(
            'previewCtrl', function ($scope, $sce) {
                $scope.url = $sce.trustAsRecourseUrl($scope.url);
            });
    </script>
</head>
<body ng-app="PresentationApp" ng-controller="previewCtrl" class="grey lighten-4">
<? if ($user->isLoggedIn()) {
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
    <main>
        <?php
        if ($canEdit) {
            ?>
            <div class="row">
                <div class="card col s10 offset-s1" style="padding:30px 0;">
                    <div class="card-content">
                        <? if (Session::exists('presentationSaved')) {
                            echo '<div class="center-align grey-text">' . Session::flash
                                ('presentationSaved') . '</div>';
                        }
                        if ($newPresMessage !== '' && $newPresMessage !== null) {
                            echo '<h4 class="center-align">' . $newPresMessage . '</h4>';
                        } ?>
                        <form method="post" action="">
                            <div class="position-top">
                                <button class="btn-flat green waves-effect waves-light white-text"
                                        type="submit">Save
                                </button>
                            </div>
                            <div class="indent align-left col s10 offset-s1 m10 offset-m1 l7">
                                <div class="input-field">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" value="<? echo $title; ?>"
                                           required="true"/>
                                </div>

                                <div class="input-field">
                                <textarea maxlength="200" style="min-height:2.4rem;"
                                          class="materialize-textarea" name="description"
                                          id="description" required="true"><? echo $description; ?></textarea>
                                    <label>Description</label>

                                    <div class="input-field">
                                        <label for="url">Add existing HTML-based presentation from URL</label>
                                        <input type="text" name="url" id="url" required="true" value="<? echo
                                        $url; ?>"/>
                                    </div>
                                    <div class="input-field">
                                        <label for="tag">Tags</label>
                                        <input type="text" list="tags" name="tag" id="tag"
                                               value="<? echo $pres_tag_name; ?>"
                                               required="true"/>
                                        <datalist id="tags">
                                            <option value="UX"></option>
                                            <option value="HTML5"></option>
                                            <option value="JavaScript"></option>
                                            <option value="Tech"></option>
                                        </datalist>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?
        } ?></main>
    <footer class="page-footer center-align deep-orange lighten-1">
        <div class="col s6 m6 offset-m3" style="padding-bottom: 22px; font-size:11pt;">
            <a class="grey-text text-lighten-3" href="terms.php">Terms of Use</a>
            <span class="white-text"> | </span>
            <a class="grey-text text-lighten-3" href="privacy.php">Privacy Policy</a>
        </div>
        <div class="white-text">&copy; 2015 Polina Stoyanova. All Rights Reserved.</div>
    </footer>
<?
}
} ?>
</body>
</html>

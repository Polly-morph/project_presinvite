<?php
require_once 'core/init.php';

//Angular only routes to this page if there is php code included as the file is saved as *.php
$pres = new Presentation();
$user = new User();
$presTags = new Presentation();
$results = $pres->getAllPresentations();
$isFav = 'false';
$has_profile_image = 'false';
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

    <!--Responsive scaling-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--Icon-->
    <meta name="msapplication-TileColor" content="#ff7043">
    <meta name="msapplicationTileImage" content="images/presInviteIconTile.png">
    <link rel="apple-touch-icon-precomposed" href="images/presInvite_icon.png">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

    <!--    Custom CSS-->
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
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.0-beta.5/angular-sanitize.js"></script>
    <!--        MaterializeCSS mobile menu-->
    <script>
        $(document).ready(function () {
            $(".button-collapse").sideNav();
        });
        function htmlEntities(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    </script>
</head>

<body ng-app="PresentationApp" ng-controller="presCtrl" ng-cloak class="grey lighten-4">
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
        <h4 class="center-align">Browse</h4>

        <div class="row">
            <div class="col s10 offset-s1 m6 offset-m3 input-field grey lighten-3" style="border-radius: 5px;">
                <i class="prefix mdi-action-search medium" style="font-size:2.5rem;"></i>
                <input type="text" ng-model="searchText">
            </div>
            <? if (Session::exists('addedToFavourites')) {
                echo '<div class="col s10 offset-s1 m6 offset-m3 center-align grey-text" style="clear:both;">' . Session::flash('addedToFavourites') . '</div>';
            } ?>
        </div>
        <div class="row">

            <script>
                angular.module('PresentationApp', []).controller(
                    'presCtrl', function ($scope) {
                        $scope.presentations = [];
                        <?
        foreach ($results as $row) {
            $userFavourites = new Favourite();
            $pres_id = $row['id'];
            $pres_user_id = $row['user_id'];
            if ($user->find($pres_user_id)) {
                $user_first_name = escape($user->data()->first_name);
                $user_last_name = escape($user->data()->last_name);
                $profile_image = escape($user->data()->profile_image);
                if ($profile_image != '' && $profile_image != null) {
                    $has_profile_image = 'true';
                } else {
                    $has_profile_image = 'false';
                }
                $email = escape($user->data()->email);
                $occupation = escape($user->data()->occupation);
                $location = escape($user->data()->location);
                $company = escape($user->data()->company);
                if ($company !== '' && $company != null) {
                    $company = 'at ' . $company;
                }
            }

            $pres_title = htmlentities($row['title'], ENT_NOQUOTES, 'UTF-8');
            $pres_desc = htmlentities($row['description'], ENT_NOQUOTES, 'UTF-8');
            $pres_tag_id = escape($row['tags']);
            if ($presTags->getTagNames($pres_tag_id)) {
                $pres_tag_name = escape($presTags->data()->tag_name);
                $pres_tag_colour = escape($presTags->data()->tag_colour);
            }
            $pres_contents = base64_decode($row['contents']);
            $userFavourites->isFavourite($user_id, $pres_id);
            $fav_id = escape($userFavourites->getCurrentId());
            if ($userFavourites->checkFavourite()) {
                $isFav = 'true';
            } else {
                $isFav = 'false';
            } ?>
                        id = parseInt(<? echo $pres_id?>);
                        presObj = {
                            pres_id: id,
                            pres_title: "<? echo $pres_title?>",
                            pres_desc:"<? echo $pres_desc ?>",
                            pres_tag_id: "<? echo $pres_tag_id?>",
                            pres_tag_name: "<? echo $pres_tag_name?>",
                            pres_tag_colour: "<? echo $pres_tag_colour?>",
                            pres_is_fav:<? echo $isFav?>,
                            pres_fav_id: "<? echo $fav_id?>",

                            pres_author_first_name: "<? echo $user_first_name?>",
                            pres_author_last_name: "<? echo $user_last_name?>",
                            pres_author_email: "<? echo $email?>",
                            pres_author_has_profile_image:<? echo $has_profile_image?>,
                            pres_author_profile_image: "<? echo $profile_image?>",
                            pres_author_occupation: "<? echo $occupation?>",
                            pres_author_company: "<? echo $company?>",
                            pres_author_location: "<? echo $location?>"
                        };
                        $scope.presentations.push(presObj);
                        <? } ?>
                    });
            </script>
            <div ng-controller="presCtrl">
                <div ng-repeat="pres in filteredResults=(presentations | filter: searchText)">
                    <div class="card small col s10 offset-s1 l5 cardPadding">
                        <div class="">
                            <h5>
                                <a class="red-text text-accent-2" href="viewPresentation.php?pres_id={{pres.pres_id}}">
                                    {{pres.pres_title}}</a>

                                <div class="align-right" ng-if="pres.pres_is_fav">
                                    <a href="removeFromFavourites.php?fav_id={{pres.pres_fav_id}}"><i class="mdi-action-star-rate
                            medium amber-text text-darken-1"></i></a>
                                </div>
                                <div class="align-right" ng-if="pres.pres_is_fav==false">
                                    <a href="addToFavourites.php?pres_id={{pres.pres_id}}"><i class="mdi-action-star-rate
                            medium
                            grey-text text-lighten-2"></i></a>
                                </div>

                            </h5>
                            <div class="grey-text text-darken-4">{{pres.pres_desc}}</div>

                        </div>
                        <div class="divider" style="margin-top:20px;margin-bottom:20px;"></div>
                        <img ng-if="pres.pres_author_has_profile_image" class="align-left circle presProfilePic" src="{{pres
                .pres_author_profile_image}}" alt="{{pres_author_first_name}}"/>

                        <i ng-if="pres.pres_author_has_profile_image==false" class="align-left mdi-action-account-circle
                    presProfilePic large grey-text"></i>

                        <div class="left-align">
                            <h6 class="grey-text text-darken-1 name">{{pres.pres_author_first_name}} {{pres
                                .pres_author_last_name}}</h6>

                            <div class="grey-text details">{{pres.pres_author_occupation}} {{pres.pres_author_company}}
                            </div>
                            <div class="grey-text details">{{pres.pres_author_location}}</div>
                            <tag class="{{pres.pres_tag_colour}} lighten-1 white-text">#{{pres.pres_tag_name}}
                            </tag>
                        </div>
                    </div>
                </div>
                <div ng-show="!filteredResults.length" class="center-align grey-text">No matches found.</div>
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
<?php }
} ?>
</body>
</html>

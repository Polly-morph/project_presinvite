<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
if ($user->isLoggedIn()) {
    $user_id = escape($user->data()->user_id);
    if ($user->find($user_id)) {
        $user_first_name = escape($user->data()->first_name);
        $user_last_name = escape($user->data()->last_name);
        $email = escape($user->data()->email);
        $social_media_text = escape($user->data()->social_media_text);
        $social_media_link = escape($user->data()->social_media_link);
        $profile_image = escape($user->data()->profile_image);
        if ($profile_image != '' && $profile_image != null) {
            $has_profile_image = 'true';
            $user_profile_image = '<img class="center-align circle" src="' . $profile_image . '" alt="profile
                        image">';
        } else {
            $has_profile_image = 'false';
            $user_profile_image = '<i class="align-left mdi-action-account-circle profilePic large white-text"></i>';
        }
        $occupation = escape($user->data()->occupation);
        $location = escape($user->data()->location);
        $company = escape($user->data()->company);

        if (Input::exists()) {
            if (Token::check(Input::get('token'))) {
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'first_name' => array(
                        'required' => true,
                        'min' => 2,
                        'max' => 50
                    ), 'last_name' => array(
                        'required' => true,
                        'min' => 2,
                        'max' => 50
                    )
                ));
                if ($validation->passed()) {
                    try {
                        $user->update(array(
                            'first_name' => $_POST['first_name'],
                            'last_name' => $_POST['last_name'],
                            'email' => $_POST['email'],
                            'occupation' => $_POST['occupation'],
                            'company' => $_POST['company'],
                            'location' => $_POST['location'],
                            'social_media_link' => $_POST['social_media'],
                            'profile_image' => $_POST['profile_image'],
                        ));
                        Session::flash('profile', 'Your profile has been updated.');
                        Redirect::to('profile.php');
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    foreach ($validation->errors() as $error) {
                        echo $error, '<br>';
                    }
                }
            }
        }
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

    <!--    Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>

    <!--    Materialize CSS -->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>

    <!--            jQuery required by MaterializeCSS-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <!-- Include JavaScript files required by Materialize -->
    <script type="text/javascript" src="js/materialize.min.js"></script>

    <!--AngularJS -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>

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
                    <li><a href="logout.php">Logout</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <h4 class="center-align">Profile</h4>

        <div class="row">
            <script>
                angular.module('PresentationApp', []).controller(
                    'userCtrl', function ($scope) {
                        id = parseInt(<? echo $user_id?>);
                        $scope.user =
                        {
                            id: id,
                            first_name: "<? echo $user_first_name?>",
                            last_name: "<? echo $user_last_name?>",
                            email: "<? echo $email?>",
                            social_media: "<? echo $social_media_link?>",
                            has_profile_image:<? echo $has_profile_image?>,
                            profile_image: "<? echo $profile_image?>",
                            occupation: "<? echo $occupation?>",
                            company: "<? echo $company?>",
                            location: "<? echo $location?>"
                        };
                    });
            </script>
            <div ng-controller="userCtrl">
                <div class="card col s10 offset-s1 l8 offset-l2 center-align" style="padding:30px 0;">
                    <div class="card-content">
                        <? if (Session::exists('profile')) {
                            echo '<div class="center-align grey-text" >' . Session::flash('profile') . '</div >';
                        } else if (Session::exists('passwordUpdated')) {
                            echo '<div class="center-align grey-text" >' . Session::flash('passwordUpdated') .
                                '</div >';
                        } ?>
                        <div class="card-title">Personal Details</div>
                        <form action="profile.php" method="post">
                            <div class="align-left col s10 offset-s1 l5 offset-l1">
                                <div class="input-field">
                                    <input type="text" id="first_name" name="first_name" value="{{user.first_name}}"
                                           required="true">
                                    <label for="first_name">First Name</label>
                                </div>
                                <div class="input-field">
                                    <input type="text" id="last_name" name="last_name" value="{{user.last_name}}"
                                           required="true">
                                    <label for="last_name">Last Name</label>
                                </div>
                                <div class="input-field">
                                    <input type="email" id="email" name="email" value="{{user.email}}" required="true">
                                    <label for="email">Email</label>
                                </div>
                                <div class="input-field">
                                    <input type="text" ng-model="user.profile_image" id="profile_image"
                                           name="profile image"
                                           value="{{user.profile_image}}">
                                    <label for="profile_image">Profile Image URL</label>
                                </div>
                            </div>
                            <div class="col s3 offset-s1 align-right">
                                <img class="profilePicPreview" ng-bind="user.profile_image" src="{{user.profile_image}}"
                                     alt="{{user
                    .first_name}}'s profile image"/>
                            </div>
                            <div style="clear:both;" class="col s10 offset-s1 l5 offset-l1 align-left">
                                <div class="input-field">
                                    <input type="text" id="occupation" name="occupation" value="{{user.occupation}}"
                                           required="true">
                                    <label for="occupation"> Occupation</label>
                                </div>

                                <div class="input-field">
                                    <input type="text" id="location" name="location" value="{{user.location}}"
                                           required="true">
                                    <label for="location"> Location</label>
                                </div>
                            </div>
                            <div class="col s10 offset-s1 l5 align-right">
                                <div class="input-field">
                                    <input type="text" id="company" name="company" value="{{user.company}}"
                                           required="true">
                                    <label for="first_name col m5 offset-m1"> Company</label>
                                </div>

                                <div class="input-field">
                                    <input type="text" id="social_media" name="social media"
                                           value="{{user.social_media}}">
                                    <label for="social_media"> Social Media</label>
                                </div>
                            </div>
                            <div class="col s3 offset-s1 m4" style="margin-top:20px; clear:both;">
                                <button type="submit" class="btn-flat waves-effect waves-light green
                    white-text medium">Update
                                </button>
                                <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
                            </div>
                        </form>
                        <div class="smallIndent">
                            <button class="btn-flat waves-effect waves-light grey medium col s6 offset-s1 m4 offset-m2"
                                    style="margin-top:20px;">
                                <a href="updatePassword.php" class="white-text center-align" style="margin-right:0;">Change
                                    Password</a>
                            </button>
                        </div>
                    </div>
                </div>
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
<? } ?>
</body>
</html>

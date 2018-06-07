<?php
require_once 'core/init.php';

$user = new User();
$user_id = escape($user->data()->user_id);

$pres = new Presentation();
$presUser = new User();
$pres_id = (int)$_GET['pres_id'];

$invitation = new Invitation();
$checkInvitation = new Invitation();
$canSend = true;

if (!$user->isLoggedIn() || $pres_id == 0) {
    Redirect::to('login.php');
}
if ($user->exists()) {

if ($user->find($user_id)) {
    $user_first_name = escape($user->data()->first_name);
    $user_last_name = escape($user->data()->last_name);

    $user_occupation = escape($user->data()->occupation);
    $user_company = escape($user->data()->company);
    if ($company !== '' && $company != null) {
        $company = 'at ' . $company;
    }
    $user_location = escape($user->data()->location);
    $user_social_media = escape($user->data()->social_media_id);
    $user_email = escape($user->data()->email);
}

if ($pres->find($pres_id)) {
    try {
        $pres_title = escape($pres->data()->title);
        $pres_user_id = escape($pres->data()->user_id);
        if ($presUser->find($pres_user_id)) {
            $pres_author_first_name = escape($presUser->data()->first_name);
            $pres_author_last_name = escape($presUser->data()->last_name);
            $pres_author_email = escape($presUser->data()->email);
        }
    } catch (PDOException $e) {
        //fix exception catch method
        die($e->getMessage());
    }
}
if ($checkInvitation->preventMultipleInvites($user_id, $pres_user_id)) {
    $canSend = false;
}
if ($canSend == false) {
    $inviteDate = escape($checkInvitation->getInviteDate());
    if ($inviteDate) {
        $reminderDate = DateTime::createFromFormat('Y-m-d H:i:s', $inviteDate);
        $reminderDate->add(new DateInterval(P2D));
        $dateAvailable = $reminderDate->format('l j F');
        $invitation_status = 'You have already sent an invitation to ' . $pres_author_first_name . ' ' . $pres_author_last_name . '. You
        can remind them after ' . $dateAvailable . '.';
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

<body ng-app="PresentationApp" ng-cloak class="grey lighten-4">

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
        <div class="row">
            <?php
            if ($canSend == false) {
                ?>
                <h4 class="center-align">Invitation Sent</h4>
                <div class="card col s10 offset-s1 l8 offset-l2">
                    <div class="card-content">
                        <div class="details grey-text center-align"><?php echo $invitation_status; ?></div>
                    </div>
                </div>
            <? } else { ?>
                <div class="card col s10 offset-s1 l8 offset-l2" ng-controller="inviteCtrl">
                    <div class="card-content">
                        <h4 class="card-title red-text text-accent-2 center-align">Invite speaker</h4>

                        <form action="sendInvite.php" method="post">

                            <div class="input-field  col s12 m6">
                                <input type="text" name="first_name" id="first_name" autocomplete="off" value="<?
                                echo $user_first_name; ?>" required="true" disabled>
                                <label for="first_name">First name</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input type="text" name="last_name" id="last_name" autocomplete="off" value="<?php
                                echo $user_last_name; ?>" required="true" disabled>
                                <label for="last_name">Last name</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="email" name="email" id="email" autocomplete="off" value="<?php echo
                                $user_email; ?>" required="true" disabled>
                                <label for="email">Email</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input type="text" name="event_url" id="event_url" autocomplete="off">
                                <label for="email">Event URL</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input type="text" name="event" id="event" autocomplete="off" ng-model="event.name"
                                       required="true">
                                <label for="event">Event</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="date" name="event_date" id="event_date" required="true">
                                <label for="event_date">Event Date</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input type="text" name="city" id="city" autocomplete="off" value="" required="true">
                                <label for="city">City</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input type="text" name="postcode" id="postcode" autocomplete="off" value="">
                                <label for="postcode">Postcode</label>
                            </div>
                            <div class="input-field col s12">
                                <input type="text" name="subject" id="subject" autocomplete="off" value="<? echo
                                    'Invitation to present ' . $pres_title; ?> at {{event.name}}" required>
                                <label for="subject">Subject</label>
                            </div>
                            <div class="input-field col s12">
                            <textarea name="invitation_text" id="invitation_text"
                                      class="materialize-textarea" required="true"></textarea>
                                <label for="invitation_text">Invitation Body</label>
                            </div>
                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                            <input type="hidden" name="pres_id" value="<?php echo $pres_id; ?>">
                            <button class="btn-flat waves-effect waves-green green white-text medium col s6 m2
                        align-left smallIndent" type="submit">Send
                            </button>
                        </form>
                    </div>
                </div>
            <?
            } ?>
        </div>
    </main>
    <script>
        $(".datepicker").pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 1 // Creates a dropdown of 15 years to control year
        });
    </script>
    <!--     Custom AngularJS Controllers-->
    <script>
        angular.module('PresentationApp', [])
            .controller('inviteCtrl', ['$scope', function ($scope) {
                $scope.event = {
                    name: ''
                };
            }]);
    </script>
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
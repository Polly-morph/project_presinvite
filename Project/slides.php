<?php
require_once 'core/init.php';

$pres = new Presentation();
$user = new User();
$pres_id = (int)$_GET['pres_id'];
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
if ($user->exists()) {
if ($pres->find($pres_id)) {
    try {
        $contents = base64_decode(escape($pres->data()->contents));
        $title = escape($pres->data()->title);
        $description = escape($pres->data()->description);
        $pres_user_id = escape($pres->data()->user_id);

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
            $social_media = escape($user->data()->social_media_id);

            $email = escape($user->data()->email);
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

    <!--AngularJS -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>

    <script>
        $(document).ready(function () {
            $(".button-collapse").sideNav();
        });
    </script>
</head>

<body ng-app="PresentationApp" class="grey lighten-4">
<main>
    <!--        Check if user is logged in and display page contents-->
    <?
    if ($user->isLoggedIn()) {
        $user_id = escape($user->data()->user_id);
        $email = escape($user->data()->email);
        $user_first_name = escape($user->data()->first_name);
        ?>
        <div>
            <?php echo $contents; ?>
        </div>
    <?php }
    } ?>
</main>
</body>
</html>
<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');

} else {
    $user_id = escape($user->data()->user_id);
    $email = escape($user->data()->email);
    $user_first_name = escape($user->data()->first_name);
    $user_profile_image = escape($user->data()->profile_image);
    if ($user_profile_image != '' && $user_profile_image != null) {

        $user_profile_image = '<img class="center-align circle" src="' . $user_profile_image . '" alt="profile
                        image">';
    } else {
        $user_profile_image = '<i class="align-left mdi-action-account-circle profilePic large white-text"></i>';

    }

    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'password_current' => array(
                    'required' => true,
                    'min' => 6),
                'password_new' => array(
                    'required' => true,
                    'min' => 6),
                'password_new_again' => array('required' => true,
                    'min' => 6,
                    'matches' => 'password_new')
            ));
            if ($validation->passed()) {
                //change password
                if (Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password) {
                    {
                        echo 'Your current password is wrong';
                    }
                } else {
                    $salt = Hash::salt(32);
                    $user->update(array(
                        'password' => Hash::make(Input::get('password_new'), $salt),
                        'salt' => $salt
                    ));
                    Session::flash('passwordUpdated', 'Your password has been updated.');
                    Redirect::to('profile.php');
                }
            } else {
                foreach ($validation->errors() as $error) {
                    echo $error, '<br>';
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
<body>
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
    <!--******* PASSWORD CHANGE FORM START ******* -->
    <main>
        <div class="row">
            <div class="card col s10 offset-s1 m8 offset-m2 l6 offset-l3">
                <div class="card-content">
                    <h3 class="card-title red-text text-accent-2 center-align">Change Password</h3>

                    <form action="" method="post">
                        <div class="input-field">
                            <input type="password" name="password_current" id="password_current" autocomplete="off">
                            <label for="password_current">Current Password</label>
                        </div>
                        <div class="input-field">
                            <input type="password" name="password_new" id="password_new" autocomplete="off">
                            <label for="password_new">New Password</label>
                        </div>
                        <div class="input-field">
                            <input type="password" name="password_new_again" id="password_new_again"
                                   autocomplete="off">
                            <label for="password_new_again">Confirm New Password</label>
                        </div>
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <button class="btn-flat waves-effect waves-green green white-text"
                                type="submit">Update
                        </button>
                    </form>
                </div>
            </div>
            <!--*******PASSWORD CHANGE FORM END*******-->
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
<?php
require_once 'core/init.php';

$user = new User();
if ($user->isLoggedIn()) {
    Redirect::to('index.php');
} else {
if (Session::exists('registeredEmail')) {
    $registeredEmail=Session::get('registeredEmail');
    //Session::delete('registeredEmail');
}
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'email' => array('required' => true),
                'password' => array('required' => true)
            ));
            if ($validation->passed()) {
                //log in user
                $user = new User();
                $login = $user->login(Input::get('email'), Input::get('password'));
                if ($login) {
                    Session::flash('home', 'Successful login');
                    Redirect::to('index.php');
                } else {
                    echo 'Could not login.';
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
    <!-- Include JavaScript files required by Materialize -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>

    <!--AngularJS -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>
</head>

<body>
<nav class="deep-orange lighten-1 white-text">
    <div class="nav-wrapper logo align-center">PresInvite</div>
</nav>
<div class="container">
    <!--******* LOGIN FORM START ******* -->
    <div class="row">
        <div class="card col s12 m10 offset-m1">
            <div class="card-content">
                <?
                if (Session::exists('registered')) {
                    echo '<div class="center-align grey-text">' . Session::flash('registered') . '</div>';
                }
                ?>
                <h3 class="card-title red-text text-accent-2 center-align">Log in</h3>

                <div class="center-align">
                    Want to join and share great presentations? <a href="register.php">Register</a>
                </div>
                <form action="" method="post">
                    <div class="input-field">
                        <input type="email" name="email" id="email" autocomplete="off" value="<? echo
                        $registeredEmail; ?>">
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" id="password" autocomplete="off">
                        <label for="password">Password</label>
                    </div>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

                    <button class="btn-flat waves-effect waves-green green white-text"
                            type="submit">Login
                    </button>
                </form>
                <!--                <div class="card-action">-->
                <!--                                               <a href="resetPassword.php">Reset Password</a>-->
                <!--                </div>-->
            </div>
        </div>
    </div>
    <!--*******LOGIN FORM END*******-->
</div>
</body>
</html>
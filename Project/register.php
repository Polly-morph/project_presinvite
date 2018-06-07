<?php
require_once 'core/init.php';

if(Input::exists()){
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'first_name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'last_name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'email' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            )

        ));

        if($validation->passed()){
            //register user
           $user=new User();
            $salt=Hash::salt(32);

            try{
                $user->create(array(
                    'email'=>Input::get('email'),
                    'password' =>Hash::make(Input::get('password'),$salt),
                    'salt' =>$salt,
                    'first_name' =>Input::get('first_name'),
                    'last_name' =>Input::get('last_name'),
                    'joined'=>date('Y-m-d H:i:s'),//human readable date passed onto the datetime field in the database
                    'group' =>2
                ));
                Session::flash('registered', 'You have been successfully registered.');
                Session::put('registeredEmail',Input::get('email'));
                Redirect::to('login.php');
            }catch(Exception $e){
                die($e->getMessage());
                //redirect user to a page asking to try and register again
                Redirect::to('register.php');
            }
        }
        else{
            //output errors
            foreach($validation->errors() as $error) {
                echo $error, '<br/>';
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
<nav class="deep-orange lighten-1 white-text">
    <div class="nav-wrapper logo align-center">PresInvite</div>
</nav>
    <div class="container">
        <!--******* REGISTER FORM START ******* -->
        <div class="row">
            <div class="card col s12 m10 offset-m1 l8 offset-l2">
                <div class="card-content">
                    <h3 class="card-title red-text text-accent-2 center-align">Register</h3>
                    <div class="center-align">
                       Already signed up?<a href="login.php"> Log in</a>
                    </div>
                    <!--action is left empty so that the form submits to the same page-->
                    <form action="register.php" method="post">
                        <div class="input-field">
                            <input type="text" id="first_name" name="first_name" value="<?php echo escape(Input::get
                            ('first_name')); ?>" required="true">
                            <label for="first_name">First Name</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="last_name" name="last_name" value="<?php echo escape(Input::get
                            ('last_name')); ?>" required="true">
                            <label for="last_name">Last Name</label>
                        </div>
                        <div class="input-field">
                            <input id="email" type="email" name="email" value="<?php echo escape(Input::get('email'));
                            ?>" required="true">
                            <!--    escape html entities in the input fields and display the lastly entered values-->
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input type="password" name="password" id="password" value="" required="true">
                            <label for="password">Password</label>
                        </div>
                        <div class="input-field">
                            <input type="password" id="password_again" name="password_again" value="" required="true">
                            <label for="password_again">Repeat password</label>
                        </div>
                        <input type="hidden" name="token" value="<?php echo Token::generate();?>">
                        <button class="btn-flat waves-effect waves-green green white-text"
                                type="submit">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
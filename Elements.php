<?php
require_once("classes/userinfo.class.php");

function createHeader($siteTitle){
echo'
<!DOCTYPE html>
<html lang="et-EE">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>'.$siteTitle.'</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/swiper.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT" rel="stylesheet"> 
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.2/TweenMax.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        
    </head>
    <body>
';
}

function createFooter()
{
    echo'
     <footer id="footer>" style="margin-top:500px;<div id="footer3" class="footer-v3"> <section class="middle-footer"> <div class="container"> <div class="row"> <div class="col-md-4 col-sm-4 col-xs-12 middle-block"> 
     <div class="footer-about"> <div class="block-title"><span class="h4"><span class="title">Meist</span></span></div> <div class="block-content"> 
     <p> Leiame loomadele hoidjad</p> </div> </div> </div> <div class="col-md-4 col-sm-4 col-xs-12 middle-block"> <div class="footer-contacts"> <div class="block-title"> <span class="h4"><span class="title">
     Kontakt</span></span> </div> <div class="block-content"> <ul> <li><em class="fa fa-map-marker"></em> 
     <span class="text">Loomaaia 14, 11223 <br>Tallinn </span></li> <li><em class="fa fa-phone"></em> 
     <span class="text">+372 6543321 / +372 5672859</span></li> <li><em class="fa fa-envelope-o"></em> <span class="text">
     <a href="mailto:madeup@gmail.com">madeup@gmail.com</a></span></li> </ul> </div> </div> </div> <div class="col-md-4 col-sm-4 col-xs-12 middle-block row-2sm-first">
      <div class="info-footer"> <div class="block-title"> <span class="h4"><span class="title">Kasulikud Lingid</span></span> </div> <div class="block-content"> <ul>
       <li><a href="http://greeny.cs.tlu.ee/~kellrasm/RakendusteProg/feed.php"><em class="fa fa-angle-right"></em> Kuulutused</a></li> <li><a href="http://greeny.cs.tlu.ee/~kellrasm/RakendusteProg/profile.php"><em class="fa fa-angle-right"></em> Profiil</a></li>
        <li><a href="http://greeny.cs.tlu.ee/~kellrasm/RakendusteProg/imgupload.php"><em class="fa fa-angle-right"></em> Kuulutuse lisamine</a></li></ul> </div>
         </div> </div> </div> </div> </section> <section class="bottom-footer"> <div class="container"> <div class="row"> <div class="col-md-12"> <address class="copy"> <span id="design-footer-copyright-text">© 2018 PetSitter</span> </address> </div> </div> </div> </section> </div></footer> 
    <script src="js/script.js"></script>
    <script src="js/swiper.min.js"></script>
    </body>
    </html>
    ';
}

function createNavbar(){
    if (isset($_SESSION["user_id"])) {
        //Get logged in user's email
        $pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
        $info = new UserInfo($_SESSION["user_id"], $pdo);
        $userEmail = $info->getEmail();
		$userName = $info->getUsername();
    echo'
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand navanim" href="#">Grupitöö</a>
        </div>
        <ul class="nav navbar-nav">
        <li class="navanim"><a href="feed.php">Kuulutused</a></li>
        <li class="navanim"><a href="myfeed.php">Minu Kuulutused</a></li>
            <li class="navanim"><a href="profile.php">Profiil</a></li>
            <li class="navanim"><a href="imgupload.php">Lisa kuulutus</a></li>
        </ul>
        <p class="navbar-text" style="float: right">Sisse logitud : <a href="profile.php">'.$userName.'</a></p>
        <form id="signout" class="navbar-form navbar-right" role="form" method="POST" action="">
        <button type="submit" class="btn btn-primary navanim" name="signoutButton">Log out</button>
        </form>
        </div>
    </nav>
        ';
        }else{
        echo'
        <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand navanim" href="#">Grupitöö</a>
          </div>
          <ul class="nav navbar-nav">
              <li class="navanim"><a href="index.php">Sisse logimine</a></li>
          </ul>
        <form id="signin" class="navbar-form navbar-right" role="form" method="POST" action="">
        <div class="input-group navanim">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="loginEmail" type="email" class="form-control" name="loginEmail" value="" placeholder="Email Address" required>                                        
        </div>

        <div class="input-group navanim">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input id="loginPassword" type="password" class="form-control" name="loginPassword" value="" placeholder="Password" required>                                        
        </div>

        <button type="submit" class="btn btn-primary navanim" name="signinButton">Login</button>
        <button type="button" class="btn btn-default navanim" data-toggle="modal" data-target="#registerModal">Registreeri</button>
    </form>
    </div>
    </nav> 
    ';
    }
    if (isset($_POST["signoutButton"])){
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
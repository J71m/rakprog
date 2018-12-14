<?php
//if logged in, redirect to main page
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: feed.php");
    exit();
}

require_once "Elements.php";
require_once "classes/Photoupload.class.php";
require "classes/useractions.class.php";
require "functions.php";
require "conn.php";
createHeader("Front Page");
createNavbar();
if (isset($_POST["signinButton"])) {
//kas on kasutajanimi sisestatud
    if (isset($_POST["loginEmail"])) {
        if (empty($_POST["loginEmail"])) {
            $loginEmailError = "NB! Sisselogimiseks on vajalik kasutajatunnus (e-posti aadress)!";
        } else {
            $loginEmail = $_POST["loginEmail"];
        }
    }
    if (!empty($loginEmail) and !empty($_POST["loginPassword"])) {
        //echo "sisse logimine";
        $pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
        $Login = new UserActions($pdo);
        if ($user_id = $Login->login($_POST['loginEmail'], $_POST['loginPassword'])) {
            echo 'Logged it as user id: ' . $user_id;
            $userData = $Login->getUser();
            header("Location: feed.php");
            exit();
            // do stuff
        } else {
            echo 'Invalid login';
        }
    }
}

$notice = "";
if (isset($_POST["signupButton"])) {
    echo '<script>console.log("signup pressed")</script>';
    if (isset($_POST["signupEmail"]) && isset($_POST["signupUsername"]) && isset($_POST["signupPassword"]) && isset($_POST["signupFirstname"]) && isset($_POST["signupLastname"]) && isset($_POST["signupGender"]) && isset($_POST["signupBio"]) && isset($_FILES["signupImage"])) {
        echo '<script>console.log("all variables set")</script>';

        //IMAGE LOGIC
        $imageFileType = strtolower(pathinfo(basename($_FILES["signupImage"]["name"]))["extension"]);
        $timeStamp = microtime(1) * 10000;
        $target_file = "img_" . $timeStamp . "." . $imageFileType;
        $target_thumb = "img_" . $timeStamp . "." . $imageFileType;
        $thumb_dir = "img/profile_thumb/";
        $target_dir = "img/profile_pics/";

        $check = getimagesize($_FILES["signupImage"]["tmp_name"]);
        if ($check !== false) {
            $notice .= "Fail on pilt - " . $check["mime"] . ". ";
            $uploadOk = 1;
        } else {
            $notice .= "See pole pildifail. ";
            $uploadOk = 0;
        }
        //Kas saab laadida?
        if ($uploadOk == 0) {
            $notice .= "Vabandust, pilti ei laetud üles! ";
            //Kui saab üles laadida
        } else {

            //Kas selline pilt on juba üles laetud
            if (file_exists($target_file)) {
                $notice .= "Kahjuks on selle nimega pilt juba olemas. ";
                $uploadOk = 0;
            }
            //Piirame failitüüpe
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
                $uploadOk = 0;
            }
            //initialize class
            $myProfilePic = new Photoupload($_FILES["signupImage"]["tmp_name"], $imageFileType);
            //Upload image with watermarks
            $myProfilePic->resizePhoto(600, 600);
            //$myProfilePic->addWatermark("graphics/watermark.png", $marginRight, $marginBottom);
            //$myProfilePic->addTextWatermark("Heade mõtete veeb");
            $myProfilePic->savePhoto($target_dir, $target_file);
            $myProfilePic->clearImages();

            //Upload thumbnail
            $myProfilePic->resizePhoto(300, 300);
            $myProfilePic->savePhoto($thumb_dir, $target_thumb);
            $myProfilePic->clearImages();
            unset($myProfilePic);
            $mysqli = new mysqli('localhost', 'if17', 'if17', 'if17_kellrasm');
            $stmt = $mysqli->prepare("INSERT INTO pic_group (userid, filename, thumbnail) VALUES (?, ?, ?)");
            echo $mysqli->error;
            $user = $_SESSION["user_id"];
            $filenamedb = "img_" . $timeStamp . "." . $imageFileType;
            $thumbnamedb = "img_" . $timeStamp . "." . $imageFileType;

            $imageFileType = strtolower(pathinfo(basename($_FILES["signupImage"]["name"]))["extension"]);
            $timeStamp = microtime(1) * 10000;
            $pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
            $Register = new UserActions($pdo);
            $Register->registerUser($_POST["signupEmail"], $_POST["signupPassword"], $_POST["signupUsername"], $_POST["signupFirstname"], $_POST["signupLastname"], $_POST["signupGender"], $_POST["signupBio"], $thumbnamedb);
            echo 'Successfully registered!';
            $Register->login($_POST['signupEmail'], $_POST['signupPassword']);
            header("Location: feed.php");
        }
    } else {
        $notice = "Palun valige kõigepealt pildifail!";

    }
}
echo $notice;
?>

<div id="registerModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Registreeri siin</h4> <p style="color:red" id="msg"></p>
      </div>
      <div class="modal-body">
            <form class="form-horizontal" role="form" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                    <label for="signupEmail" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" id="signupEmail" name="signupEmail" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signupUsername" class="col-sm-3 control-label">Kasutajanimi</label>
                    <div class="col-sm-9">
                        <input type="text" id="signupUsername" name="signupUsername" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signupPassword" class="col-sm-3 control-label">Parool</label>
                    <div class="col-sm-9">
                        <input type="password" id="signupPassword" name="signupPassword" placeholder="Password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signupFirstName" class="col-sm-3 control-label">Eesnimi</label>
                    <div class="col-sm-9">
                        <input type="text" id="signupFirstname" name="signupFirstname" class="form-control"  required>
                    </div>
                </div>
                <div class="form-group">
                <label for="signupLastname" class="col-sm-3 control-label">Perekonnanimi</label>
                <div class="col-sm-9">
                    <input type="text" id="signupLastname" name="signupLastname" class="form-control" required>
                </div>
                </div>
                <div class="form-group">
                    <label for="signupGender" class="col-sm-3 control-label">Sugu</label>
                    <div class="col-sm-9">
                    <select class="form-control" name="signupGender" id="signupGender" required>
                        <option value=1>Mees</option>
                        <option value=2>Naine</option>
                        <option value=3>Ei avalda</option>
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signupBio" class="col-sm-3 control-label">Biograafia</label>
                    <div class="col-sm-9">
                        <textarea id="signupBio" name="signupBio" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                <label for="signupImage" class="col-sm-3 control-label">Profiilipilt</label>
                <div class="col-sm-9">
                <input type="file" name="signupImage" id="signupImage" accept="image/*">
                </div>
            </div>
          </div>
      <div class="modal-footer">
        <button type="submit" name="signupButton" id="signupButton" class="btn btn-primary">Registreeri</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </form>
      </div>
       <!-- /form -->
    </div>

  </div>
</div>

<style>
    html, body {
      position: relative;
      height: 100%;
    }

    body {
      background: #eee;
      font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
      font-size: 14px;
      color:#000;
      margin: 0;
      padding: 0;
      background-image: url("img/background.jpg");
    }
    .swiper-container {
      width: 100%;
      height: 100%;

    }
    .swiper-slide {

      font-size: 50px;
      /* Center slide text vertically */
      display: -webkit-box;
      display: -ms-flexbox;
      display: -webkit-flex;
      display: flex;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      -webkit-justify-content: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      -webkit-align-items: center;
      align-items: center;
    }

    .parallax-bg {
      position: absolute;
      left: 0;
      top: 0;
      width: 130%;
      height: 100%;
      -webkit-background-size: cover;
      background-size: cover;
      background-position: center;
    }

    .frontPageText1{
        text-align:center;
        color: whitesmoke;
        text-shadow: 2px 2px 14px black;
    }
    .frontPageText2{
        text-align:center;
        color: whitesmoke;
        text-shadow: 2px 2px 14px black;
    }
    .frontPageText3{
        text-align:center;
        color: whitesmoke;
        text-shadow: 2px 2px 14px black;
    }
    .navbar-inverse{
        opacity:0.9;
    }
  </style>

<div class="swiper-container">
<div class="parallax-bg" style="background-image:url(img/background.jpg)" data-swiper-parallax="-20%"></div>
    <div class="swiper-wrapper">
      <div class="swiper-slide"><div class="frontPageText1"><br> Petsitter</div></div>
      <div class="swiper-slide"><div class="frontPageText2">Liikmed: <br>Rasmus Kello<br> Hendrik Heinsar<br> Jaroslava Koger<br> Tim Jaanson</div></div>
      <div class="swiper-slide"><div class="frontPageText3">Rakenduste programmeerimine <br> Tallinna Ülikool <br> 2018</div></div>
    </div>
    <div class="swiper-scrollbar"></div>
  </div>


<!--
<?php
//createFooter();
?>

swiper and misc dynamic functionality
-->
     <footer id="footer>" style="margin-top:-5px"><div id="footer3" class="footer-v3"> <section class="middle-footer"> <div class="container"> <div class="row"> <div class="col-md-4 col-sm-4 col-xs-12 middle-block"> 
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



  <script>
    var swiper = new Swiper('.swiper-container', {
        speed: 600,
        parallax: true,
        scrollbar: {
          el: '.swiper-scrollbar',
          hide: true,
        },
      });


var columns = document.getElementsByClassName("frontPageText1");
TweenMax.staggerFrom(columns, 1, {
    opacity: 0,
    scale: 0,
    ease: Bounce.easeOut,
    delay: 0
}, 0.5);
  </script>
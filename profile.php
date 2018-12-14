<style>
body {
    background-image: url("img/backgroundprof.jpg");
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    height:100%;
    width:100%;
}
body p, body h1, body h2, body h3, body h4, body h5{
    color: white;
}
</style>
<?php
session_start();
require_once "Elements.php";
require_once "classes/userinfo.class.php";
require_once "classes/useractions.class.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$currentUser = $_SESSION["user_id"];

if (isset($_GET['username'])) {
    $Username = $_GET['username']; //Get user ID from URL
}
if (!isset($_GET['username'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
    $usr = new UserInfo($currentUser, $pdo);
    $Username = $usr->getUsername($currentUser);
    header("Location: profile.php?username=$Username");
}

$pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
$info = new UserInfo($currentUser, $pdo);
$userInfo = $info->getByUsername($Username);
$userId = $info->getIdByUsername($Username);
$userAnimals = $info->getUserAnimals($userId);
$userRatings = $info->getUserRatings($userId);
//if not logged in, redirect to index page

createHeader("Profile");
createNavbar();
$profilePictureSource = "img/profile_thumb/";
$profilePictureName = "";

if (isset($_POST["changeButton"])) {
    echo '<script>console.log("Change button pressed")</script>';
    if (isset($_POST["changeFirstname"]) && isset($_POST["changeLastname"]) && isset($_POST["changeGender"]) && isset($_POST["changeBio"])) {
        echo '<script>console.log("All field values present")</script>';
        $pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
        $changeUser = new UserActions($pdo);
        $changeUser->changeProfile($_SESSION["user_id"], $_POST["changeFirstname"], $_POST["changeLastname"], $_POST["changeGender"], $_POST["changeBio"]);
        header("Location: profile.php?$currentUser");
    }
}
?>
<div class="container">
<div class="row">
<div class="col col-md-6 anim">
<?php
//Get and print user object
if (!empty((array) $userInfo)) {

    if ($userInfo[0]->sexId == 2) {
        $userInfo[0]->sexId = "mees";
    } else if ($userInfo[0]->gender == 3) {
        $userInfo[0]->sexId = "naine";
    } else {
        $userInfo[0]->sexId = "salajane";
    }

    $pdo2 = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
    $currentUsernameFetch = new UserInfo($currentUser, $pdo2);
    $currentUsername = $currentUsernameFetch->getUsernameById($currentUser);

    if ($currentUsername == $_GET["username"]) {
        echo '<button type="button" class="btn btn-primary navanim" data-toggle="modal" data-target="#profileModal">Redigeeri Profiili</button>';
    }
    echo '<h3>' . $Username . '</h3>';
    echo '<p>Email: ' . $userInfo[0]->email . ' </p>';
    echo '<p>Username: ' . $userInfo[0]->userName . ' </p>';
    echo '<p>Eesnimi: ' . $userInfo[0]->firstName . ' </p>';
    echo '<p>Perekonnanimi: ' . $userInfo[0]->lastName . ' </p>';
    echo '<p>Sugu: ' . $userInfo[0]->sexId . ' </p>';
    $profilePictureName = $userInfo[0]->pathToPic;
    $imgDir = "img/";
    $picFileTypes = ["jpg", "jpeg", "png", "gif"];
    $picFiles = [];

    $allFiles = array_slice(scandir($imgDir), 2);
    foreach ($allFiles as $file) {
        $fileType = pathinfo($file, PATHINFO_EXTENSION);
        if (in_array($fileType, $picFileTypes) == true) {
            array_push($picFiles, $file);
        }
    }
    echo '<h3>Kasutaja loomad</h3>';
    foreach ($userAnimals as $row) {
        echo '
        <div class="col-md-4">
        <a href=photo.php?photo=' . $row["pathToPic"] . '><img src="img/animal_pics/' . $row["pathToPic"] . '" class="img-thumbnail" alt="Pilt"></a>
        <p> '. $row["type"] .' '. $row["petname"] .' </p>
        </div>
        ';
    }
} else {
    echo 'Ei leia selle nimega kasutajat';
}
?>
</div>


<div class="col col-md-6 text-center anim">
<img src="<?php
if (!empty((array) $userInfo)) {echo $profilePictureSource . $profilePictureName;}?>">
</div>
</div>

<div class="row">
<style>
.checked {
  color: orange;
}
</style>
<?php
    echo '<div class="col-md-12 anim" style="margin-bottom: 130px"><h3>Kasutajale antud hinnangud</h3>';
    foreach ($userRatings as $row) {
        $rating = $row["rating"];
        echo '
        <div class="col-md-4">
        <img src="img/profile_pics/' . $row["pathToPic"] . '" class="img-thumbnail" alt="Pilt">
        <p>'. $row["firstName"] .' '. $row["lastName"] .'<br>'. $row["ratingDesc"] .'</p>
        
        </div>
        <span class="fa fa-star '.($rating >= 1 ? "checked" : "").'"></span>
        <span class="fa fa-star '.($rating >= 2 ? "checked" : "").'"></span>
        <span class="fa fa-star '.($rating >= 3 ? "checked" : "").'"></span>
        <span class="fa fa-star '.($rating >= 4 ? "checked" : "").'"></span>
        <span class="fa fa-star '.($rating >= 5 ? "checked" : "").'"></span>
        </div>
        ';
    }
?>

</div>
</div>


<!--
<?php
//createFooter();
?>
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
var columns = document.getElementsByClassName("anim");
TweenMax.staggerFrom(columns, 0.5, {
    opacity: 0,
    scale: 0,
    delay: 0
}, 0.2);
</script>
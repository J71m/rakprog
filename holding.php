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

if (isset($_GET['id'])) {
    $id = $_GET['id']; //Get ID from URL
}

$pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
$info = new UserInfo($currentUser, $pdo);
$userInfo = $info->getByHoldingID($id);
$userInfo2 = $info->getByHoldingID2($id);
//if not logged in, redirect to index page

createHeader("Holding");
createNavbar();
$profilePictureSource = "img/animal_pics/";
$profilePictureName = "";

?>
<div class="container">
<div class="row">
<div class="col col-md-6 anim">
<?php
//Get and print user object
if (!empty((array) $userInfo)) {

    $pdo2 = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
    $currentUsernameFetch = new UserInfo($currentUser, $pdo2);
    $currentUsername = $currentUsernameFetch->getUsernameById($currentUser);

echo '<p>Looma nimi: ' . $userInfo[0]->petname . ' </p>';
    echo '<p>Loom: ' . $userInfo[0]->type . ' </p>';
    echo '<p>Hoidja: ' . $userInfo[0]->firstName . ' ' . $userInfo[0]->lastName . ' </p>';
    echo '<p>Tegevused: ' . $userInfo[0]->activities . ' </p>';
    echo '<p>Tasu: ' . $userInfo[0]->pay . ' </p>';
    echo '<p>Alguskuupäev: ' . $userInfo[0]->beginningDate . ' </p>';
    echo '<p>Lõpukuupäev: ' . $userInfo[0]->endDate . ' </p>';
    echo '<p>Asukoht: ' . $userInfo[0]->location . ' </p>';
    echo '<button disabled type="button" class="btn btn-primary navanim" data-toggle="modal" data-target="#profileModal">Hoia</button>';
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
} else {
    
    $pdo2 = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
    $currentUsernameFetch = new UserInfo($currentUser, $pdo2);
    $currentUsername = $currentUsernameFetch->getUsernameById($currentUser);


    echo '<p>Looma nimi: ' . $userInfo2[0]->petname . ' </p>';
	echo '<p>Loom: ' . $userInfo2[0]->type . ' </p>';
    //echo '<p>Hooldaja(Kui leitud): ' . $userInfo2[0]->firstName . ' ' . $userInfo2[0]->lastName . ' </p>';
    echo '<p>Tegevused: ' . $userInfo2[0]->activities . ' </p>';
    echo '<p>Tasu: ' . $userInfo2[0]->pay . ' </p>';
    echo '<p>Alguskuupäev: ' . $userInfo2[0]->beginningDate . ' </p>';
    echo '<p>Lõpukuupäev: ' . $userInfo2[0]->endDate . ' </p>';
    echo '<p>Asukoht: ' . $userInfo2[0]->location . ' </p>';
    echo '<button type="button" class="btn btn-primary navanim" data-toggle="modal" data-target="#profileModal">Hoia</button>';
    $profilePictureName = $userInfo2[0]->pathToPic;
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
}
?>
</div>


<div class="col col-md-6 text-center anim">
<img style="max-height:400;" src="<?php
if (!empty((array) $userInfo2)) {echo $profilePictureSource . $profilePictureName;}?>">
</div>
</div>

<div class="row">
<style>
.checked {
  color: orange;
}
</style>


</div>
</div>


<!--
<?php
//createFooter();
?>
-->

     <footer id="footer>" style="margin-top:300px"><div id="footer3" class="footer-v3"> <section class="middle-footer"> <div class="container"> <div class="row"> <div class="col-md-4 col-sm-4 col-xs-12 middle-block"> 
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
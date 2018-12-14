<style>body {background-image: url("img/backgroundpic.jpg"); }
body p, body h1, body h2, body h3, body h4, body h5{
    color: white;
}
 </style>
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
require_once "Elements.php";
require_once "classes/userinfo.class.php";
require_once "classes/useractions.class.php";
createHeader("Feed");
createNavbar();
?>
<div class="container">
<h1>Hoidmiskuulutused</h1>
<?php
$currentUser = $_SESSION["user_id"];
$pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
$usernameFetch = new UserInfo($currentUser, $pdo);
$stmt = $pdo->prepare("SELECT rp_Animals.petname, rp_Animals.type, rp_Animals.description, rp_Animals.pathToPic, holdingID, beginningDate, endDate, pay, activities, extraInformation, complete from rp_Holdings inner join rp_Animals on rp_Animals.ID = rp_Holdings.animalID order by beginningDate DESC");
if (false === $stmt) {
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$stmt->execute();
if (false === $stmt) {
    die('execute() failed: ' . htmlspecialchars($mysqli->error));
}
$result = $stmt->fetchAll();
foreach ($result as $row) {

    echo '
    <div class="row">
    <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="img/animal_pics/' . $row["pathToPic"] . '">
      <div class="caption">
        <h3 style="color:black">' . $row["type"] . ' ' . $row["petname"] . '</h3>
        <p style="color:black">Kirjeldus : ' . $row["description"] . '</p>
        <p style="color:black">Alguskuupäev : ' . $row["beginningDate"] . '</p>
        <p style="color:black">Lõppkuupäev : ' . $row["endDate"] . '</p>
        <p style="color:black">Tasu : ' . $row["pay"] . '</p>
        <p style="color:black">Vajadused : ' . $row["activities"] . '</p>
        <p><a href="http://greeny.cs.tlu.ee/~kellrasm/RakendusteProg/holding.php?id='.$row["holdingID"].'" class="btn btn-primary" role="button">Vaata</a></p>
      </div>
    </div>
  </div>
</div>
    ';
}

?>
</div>
<?php
createFooter();
?>

<script>
var columns = document.getElementsByClassName("feedelementanim");
TweenMax.staggerFrom(columns, 0.5, {
    opacity: 0,
    scale: 0,
    delay: 0
}, 0.2);
</script>
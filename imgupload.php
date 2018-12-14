<style>body {background-image: url("img/backgroundpic.jpg"); }
body p, body h1, body h2, body h3, body h4, body h5, body td, body th, body label{
    color: white;
}
 </style>
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
require 'classes/useractions.class.php';
require 'conn.php';
require_once "Elements.php";
createHeader("Lisa kuulutus");
createNavbar();
require_once 'classes/Photoupload.class.php';
$notice = "";
$currentUser = $_SESSION["user_id"];
//kui logib välja
if (isset($_GET["logout"])) {
    //lõpetame sessiooni
    session_destroy();
    header("Location: index.php");
}

//Kas vajutati üleslaadimise nuppu
if (isset($_POST["submit"])) {
    if (isset($_POST["animalId"]) && isset($_POST["holdingActions"]) && isset($_POST["holdingPay"]) && isset($_POST["startingDate"]) && isset($_POST["endDate"]) && isset($_POST["location"])) {
        $animalID = $_POST["animalId"];
        $activities = $_POST["holdingActions"];
        $pay = $_POST["holdingPay"];
        $startingdate = $_POST["startingDate"];
        $endDate = $_POST["endDate"];
        $location = $_POST["location"];
        echo '<script>console.log("all variables set")</script>';
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            /*$info = new UserActions($pdo);
            $notice = $info->addHolding($_POST["animalId"], $_POST["holdingActions"], $_POST["holdingPay"], $_POST["startingDate"], $_POST["endDate"], $_POST["location"], 0);*/
            $sql = "INSERT INTO rp_Holdings (animalID, activities, pay, beginningDate, endDate, location, complete) VALUES ($animalID, '$activities', '$pay', '$startingdate', '$endDate', '$location', 0)";
            $pdo->exec($sql);
            echo "New record created successfully";
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }

        $pdo = null;
    } else {
        echo '<script>console.log("all variables not set")</script>';
    }
}

?>
<body>

    <div class="container">
    <form action="imgupload.php" method="post" class="col-md-8" enctype="multipart/form-data">
    <div class="form-group anim">
    <h3>Kuulutuse lisamine</h3>
        <div class="input-group">
        <div class="form-group row">
        <label class="col-md-4 control-label" for="animalId">Loom</label>
        <div class="col-sm-9">
            <select class="form-control" id="animalId" name="animalId">
<?php
$pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
$stmt = $pdo->prepare("SELECT ID, petname from rp_Animals WHERE ownerID=$currentUser");
if (false === $stmt) {
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$stmt->execute();
if (false === $stmt) {
    die('execute() failed: ' . htmlspecialchars($mysqli->error));
}
$result = $stmt->fetchAll(); 
foreach ($result as $row) {
    echo '<option value="'.$row["ID"].'">'.$row["petname"].'</option>';
}
?>
            </select>
            </div>
        </div>
                <div class="form-group row">
                     <label for="endDate" class="col-md-4 control-label">Alguse kuupäev</label>
                    <div class="col-sm-9">
                        <input type="date" id="startingDate" name="startingDate" class="form-control input-md" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="endDate" class="col-md-4 control-label">Lõpu kuupäev</label>
                    <div class="col-sm-9">
                        <input type="date" id="endDate" name="endDate" class="form-control input-md" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="location" class="col-md-4 control-label">Asukoht</label>
                    <div class="col-sm-9">
                        <input type="text" id="location" name="location" class="form-control input-md" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="holdingPay" class="col-md-4 control-label">Tasu</label>
                    <div class="col-sm-9">
                        <input type="text" id="holdingPay" name="holdingPay" class="form-control input-md"  required>
                    </div>
                </div>
                <div class="form-group row">
                <label for="holdingActions" class="col-md-4 control-label">Tegevused</label>
                    <div class="col-sm-9">
                        <input type="text" id="holdingActions" name="holdingActions" class="form-control input-md" required>
                    </div>
                </div>

        <input type="submit" value="Loo kuulutus" class="btn btn-default anim" name="submit" id="submit"><span id="fileSizeError"></span>
        </div></div>
    </form>
    <span><?php echo $notice; ?></span>


<?php
$images = glob("img/profile_pics*.{jpg,jpeg,png,gif}", GLOB_BRACE);
foreach ($images as $image) {
    echo '<div class="col-md-2">';
    echo '<img style="height:120px;" alt="" class="thumbnail img-responsive anim" src="' . $image . '">';
    echo '</div>';
}
?>


</div>

<?php
createFooter();
?>
<script>
var columns = document.getElementsByClassName("anim");
TweenMax.staggerFrom(columns, 1.5, {
    opacity: 0,
    x: -200,
    delay: 0
}, 0.2);
</script>
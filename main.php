<?php
session_start();
require_once "Elements.php";
require_once "classes/useractions.class.php";
//if not logged in, redirect to index page
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

createHeader("Logged in");
createNavbar();
//Get user object
$pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
$currentUser = $_SESSION["user_id"];
$info = new UserInfo($currentUser, $pdo);
$userInfo = $info->getAllInfo();
$userPhotos = $info->getUserPhotos($currentUser);

if (isset($_POST["changeButton"])) {
    echo '<script>console.log("Change button pressed")</script>';
    if (isset($_POST["changeFirstname"]) && isset($_POST["changeLastname"]) && isset($_POST["changeGender"]) && isset($_POST["changeBio"])) {
        echo '<script>console.log("All field values present")</script>';
        $pdo = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
        $changeUser = new UserActions($pdo);
        $changeUser->changeProfile($_SESSION["user_id"], $_POST["changeFirstname"], $_POST["changeLastname"], $_POST["changeGender"], $_POST["changeBio"]);
        header("Location: main.php");
    }
}

?>
<div id="profileModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Uuenda kasutaja andmeid</h4>
      </div>
      <div class="modal-body">
            <form class="form-horizontal" role="form" action="" method="POST">
                <div class="form-group">
                    <label for="changeFirstName" class="col-sm-3 control-label">Eesnimi</label>
                    <div class="col-sm-9">
                        <input type="text" id="changeFirstname" name="changeFirstname" class="form-control" value=<?php echo $userInfo[0]->firstname ?>  required>
                    </div>
                </div>
                <div class="form-group">
                <label for="changeLastname" class="col-sm-3 control-label">Perekonnanimi</label>
                <div class="col-sm-9">
                    <input type="text" id="changeLastname" name="changeLastname" class="form-control" value=<?php echo $userInfo[0]->lastname ?>  required>
                </div>
                </div>
                <div class="form-group">
                    <label for="changeGender" class="col-sm-3 control-label">Sugu</label>
                    <div class="col-sm-9">
                    <select class="form-control" name="changeGender" id="changeGender" required>
                        <option value=1>Mees</option>
                        <option value=2>Naine</option>
                        <option value=3>Ei avalda</option>
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="changeBio" class="col-sm-3 control-label">Biograafia</label>
                    <div class="col-sm-9">
                        <textarea id="changeBio" name="changeBio" class="form-control" required><?php echo $userInfo[0]->bio ?></textarea>
                    </div>
                </div>
          </div>
      <div class="modal-footer">
        <button type="submit" name="changeButton" id="changeButton" class="btn btn-primary">Uuenda</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
        </form>
      </div>
       <!-- /form -->
    </div>

  </div>
</div>
<div class="container">
<?php
//print user object
if ($userInfo[0]->gender == 1) {
    $userInfo[0]->gender = "mees";
} else if ($userInfo[0]->gender == 2) {
    $userInfo[0]->gender = "naine";
} else {
    $userInfo[0]->gender = "salajane";
}
echo '<h3>Sinu profiil</h3>';
echo '<button type="button" class="btn btn-primary navanim" data-toggle="modal" data-target="#profileModal">Redigeeri Profiili</button>';
echo '<p>Email: ' . $userInfo[0]->email . ' </p>';
echo '<p>Username: ' . $userInfo[0]->username . ' </p>';
echo '<p>Eesnimi: ' . $userInfo[0]->firstname . ' </p>';
echo '<p>Perekonnanimi: ' . $userInfo[0]->lastname . ' </p>';
echo '<p>Sugu: ' . $userInfo[0]->gender . ' </p>';
echo '<p>Biograafia: ' . $userInfo[0]->bio . ' </p>';
echo '<p>Profiilipilt: <img src="img/profile_thumb/' . $userInfo[0]->profile_pic . '"</img> </p>';
echo '<p>Kasutaja loodud: ' . $userInfo[0]->created . ' </p>';

echo '<h3>Sinu galerii</h3>';
foreach ($userPhotos as $row) {
    echo '<img src="img/thumbs/' . $row["thumbnail"] . '" class="img-thumbnail" alt="Pilt">';
}
?>

</div>
</body>

<?php
createFooter();
?>
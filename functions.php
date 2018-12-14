<?php

/*session_start();
$database = "if17_kellrasm";

function signIn($email, $password) {
$notice = "";
$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
$stmt = $mysqli->prepare("SELECT id, email, password FROM user_group WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->bind_result($id, $emailFromDB, $passwordFromDB);
$stmt->execute();

if ($stmt->fetch()) {
$hash = hash("sha512", $password);
if ($hash == $passwordFromDB) {
$notice = "Kõik ok";
//sessiooni muutujad
$_SESSION["userId"] = $id;
$_SESSION["userEmail"] = $emailFromDB;
//header("Location: main.php");
exit();
}else{
$notice = "Vale pass";
}
}else{
$notice = "Kasutajat (" .$email .") ei leitud";
}
$stmt->close();
$mysqli->close();
return $notice;
}
 */

$database = "if17_kellrasm";

function getName($profilePictureSource)
{

}

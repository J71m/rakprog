<?Php
//Get Username
@$user=$_GET['user'];
//Require PDO connection
require "conn.php";
$sql="SELECT userName FROM rp_Users WHERE username='$user'";
$row=$bdd->prepare($sql);
$row->execute();
$result=$row->fetchAll(PDO::FETCH_ASSOC);

$main = array('data'=>$result);
echo json_encode($main);
?>
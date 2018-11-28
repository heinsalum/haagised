<?php
$target_dir = "profilepics/";
$uploadOk = 1;
$nameError = "";
$commentError = "";
session_start();
if(!isset($_SESSION['username'])){
	header('Location: frontpage.php');
}
require "../../../config.php";
if(isset($_POST["submit"])){
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		} else {
			echo "Sorts, midagi läks valesti";
		}
	$database = "if17_lahtsten";
	$trailerName = $_POST['trailerName'];
	$trailerDesc = $_POST['comment'];
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$query = $mysqli->prepare ("INSERT INTO trailerinfo(trailername, trailerpic, trailerdesc, Email) VALUES (?,?,?,?)");
	echo $mysqli->error;
	$query->bind_param("ssss", $trailerName, $target_file, $trailerDesc, $_SESSION['email']);
	if($query->execute()){
		header('Location: upload.php');
	}else{
		echo "Tekkis viga!" . $query->error;
	}
}
if(isset($_POST["logout"])){
	session_unset();
	header("Location: frontpage.php");
}
$targetdir2 = "profpics/";

//if(isset($_POST['submitbtn'])){
	
//}
	
	


?>

<html lang="et">
<head>
<meta charset="utf-8">
<style>
.header{
	display: block;
	text-align:center;
	background:yellow;
}
</style>
</head>
<body>
<div class = "header">
	<h1>Tere tulemast haagisterendilehele, <?php echo $_SESSION['username'];?></h1>
	<a href = "upload.php">Vaata haagiseid</a>
	
</div>
<h2>Lisa enda haagis</h2>
<form method="post" enctype="multipart/form-data">
	<input type = "text" placeholder = "Haagise nimi" name = "trailerName">
	<br>
	<textarea name= "comment" rows = "5" cols = "40" placeholder = "Tehnilised spetsifikatsioonid" ></textarea>
	<br>
    Vali pilt, mida üles laadida:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Lae haagis üles" name="submit">
</form>
<a href = "mydata.php">Minu andmed</a>
<form method = "post">
    <input type="file" name="profilepic" id="fileToUpload">
    <input type="submit" value="Lae haagis üles" name="submitbtn">
</form>
<form method="post" enctype="multipart/form-data">
	<input name = "logout" type = "submit" value = "Logi välja">
</form>

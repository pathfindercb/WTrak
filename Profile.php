<?php
/** PAI CRUD Profile
 * package    PAI_CRUD 20201107
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and edits to the wuser table
 *	TODO: move Import to View screen
 */
// check if logged in 
session_start();
if(!isset($_SESSION["wuserid"])) {
	header("Location:Login.php");
	exit;
}

	// find the db and open it and return $pdo object
	require ("DBopen.php");
	require_once ('PAI_GoogleAuth.php');
	$ga = new PAI_GoogleAuth();
	// find the user record
	$sql = "SELECT * FROM `wuser` WHERE userid=:userid";
	$stmt = $pdo->prepare($sql);
	$val = array(userid=>$_SESSION['wuserid']);
	$stmt->execute($val);
	$r = $stmt->fetch(PDO::FETCH_ASSOC);
	//if not found then exit
	if (!$r) {header('location: Login.php');}
	// save values for form
	$email = $r['email'];
	$username = $r['username'];
	$wgoal = $r['wgoal'];
	$wgoaldate = $r['wgoaldate'];
	$scode = $r['scode'];
	
	// check if Post from submit on form below
	if(isset($_POST) & !empty($_POST)){
		if (isset($_POST['Newcode'])) {
			$scode = $ga->createSecret();
			$qrCodeUrl = $ga->getQRCodeGoogleUrl('WTrak', $scode);
		} else {	
			$email = $_POST['email'];
			$username = $_POST['username'];
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$scode = $_POST["scode"];
			$wgoal = $_POST['goal'];
			$wgoaldate = $_POST['goaldate'];
			$sql = "UPDATE `wuser` SET email=:email, username=:username, password=:password, scode=:scode, wgoal=:wgoal, wgoaldate=:wgoaldate WHERE userid=:userid";
			$val = array("userid" => $_SESSION["wuserid"],"email" => $email, "scode" => $scode, "username" => $username, "password" => $password, "wgoal" => $wgoal, "wgoaldate" => $wgoaldate);
			$stmt = $pdo->prepare($sql);
			if($stmt->execute($val)){
				header('location: Logout.php');
			}else{
				$fmsg = "Failed to update data.";
			}
		} 
	} 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
 
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
 
<link rel="stylesheet" href="styles.css" >
 
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
	<div class="row">
	<h2>Your Profile</h2>
 		<input type="button" class="btn btn-info" value="Import" onclick="location.href = 'Import.php';">
		<form method="post" class="form-horizontal col-xs-12 col-md-6 col-md-offset-3 ">
			<div class="form-group">
			    <label for="email" class="col-xs-4 control-label">Email address</label>
			    <div class="col-xs-8">
			      <input type="text" name="email"  maxlength="40" class="form-control" id="email"  value="<?php echo $email; ?>" required placeholder="Email address" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="username" class="col-xs-4 control-label">Username</label>
			    <div class="col-xs-8">
			      <input type="text" name="username"  maxlength="10" class="form-control" id="username"  value="<?php echo $username; ?>" required placeholder="Username" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="password" class="col-xs-4 control-label">Password</label>
			    <div class="col-xs-8">
			      <input type="text" name="password"  class="form-control" id="password" required  />
			    </div>
			</div>
			<div class="form-group">
			    <label for="goal" class="col-xs-4 control-label">Goal</label>
			    <div class="col-xs-8">
			      <input type="text" name="goal"  class="form-control" id="goal" required value="<?php echo $wgoal; ?>"  />
			    </div>
			</div>
			<div class="form-group">
			    <label for="goaldate" class="col-xs-4 control-label">Goal date</label>
			    <div class="col-xs-8">
			      <input type="date" name="goaldate"  class="form-control" id="goaldate" value="<?php echo $wgoaldate; ?>" required placeholder="Goal Date" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="scode" class="col-xs-4 control-label">Secret code</label>
			    <div class="col-xs-8">
			      <input type="text" readonly class="form-control-plaintext" name="scode"   required id="scode" value="<?php echo $scode; ?>" required placeholder="Secret code"/>
				 </div>
				 <div>
				  <img src="<?php echo $ga->getQRCodeGoogleUrl('WTrak', $scode); ?>"> 
				  <input type="submit" class="btn btn-primary" id="Newcode" value="Newcode" name="Newcode" />
			    </div>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary col-xs-4 col-xs-offset-7" id="submit" value="Submit" name="submit" />
				<input type="cancel" class="btn btn-warning col-xs-4 col-xs-offset-7" value="cancel" onClick="window.location='View.php';"/>
			</div>
		</form>
	</div>
</div>

</body>
</html>
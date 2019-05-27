<!DOCTYPE html>

<!-- Forgot Password 05/27/19 -->
<!--	This is the main web index for all the CRUD file maintenance using a form to select-->

<html>
<head>
<title>WTrak Menu v1.0</title>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
 
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<?php
	session_start();
	unset($_SESSION["wuserid"]);
	register_shutdown_function('shutDownFunction');
	require ("DBopen.php");
	include ("PAI_crypt.class.php");
	//get the secret key not stored in www folders
	require_once ($pfolder . 'DBkey.php');
	$paicrypt = new PAI_crypt($DBkey);
	$smsg = "Enter registered email address";
	$row = "";
	//get cookie then unset
	if(isset($_COOKIE["wemail"])) {
		$email = $_COOKIE["wemail"];
		unset($_COOKIE["wemail"]);
	} else {
		$email = "";
	}
	// first check Post from Forgot form 
	if(!empty($_POST)) {
		// check if entered userid is in wuser
		$sql = "SELECT * FROM `wuser` where email = :email AND wactive = :wactive";
		$stmt = $pdo->prepare($sql);
		$email = $_POST["email"];
		$val = array("email" => $email,"wactive" => true);
		$stmt->execute($val);
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		if($res) {
			// now email password
			$pass = $paicrypt->decrypt($res['password']);
			$to = $email;
			$subject = 'WTrak Password' ;
			$message = "Your WTrak Password" . "=" . $pass;
			$headers = 'From: cbarlow@pathfinderassociatesinc.com' . "\r\n" .
				'Reply-To: cbarlow@pathfinderassociatesinc.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			if (mail($to, $subject, $message, $headers)) {
				header("Location:Login.php");
			} else {
				$fmsg = "Mail failed";
			}
		} else {
			$fmsg = "Invalid Email" ;
		}
	}
?>
<p>
<div class="container">
	<div class="row" col-xs-6 col-xs-offset-3>
     <?php if(isset($smsg)){ ?><div class="alert alert-success col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<form name="frmUser" method="post" class="form-horizontal col-xs-12 col-md-6 col-md-offset-3">
		<h2>WTrak Forgot Password</h2>
			<div class="form-group">
			    <label for="email" class="col-xs-4 control-label">Email address</label>
			    <div class="col-xs-8">
			      <input type="text" name="email"  class="form-control" value="<?php echo $email; ?>" required id="email"/>
			    </div>
			</div>
			<div class="form-group">
			<input type="submit" name="SendPass" class="btn btn-primary col-xs-8 col-xs-offset-4 col-md-8 col-md-offset-4" value="Send Password" />
			</div>
		</form>
	</div>
</div>
<!--Footer-->
<footer class="page-footer font-small blue pt-4 mt-4">
<!--Copyright-->
    <div class="footer-copyright py-3 text-center">
        Copyright © 2019 
        <a href="http://pathfinderassociatesinc.com/"> Pathfinder Associates, Inc.</a>
    </div>
<!--/.Copyright-->
</footer>
<!--/.Footer-->

<?php

function shutDownFunction() { 
    $error = error_get_last();
    // fatal error, E_ERROR === 1
    if ($error['type'] === E_ERROR) { 
        //do your stuff
		//error_log ($_SERVER['REMOTE_ADDR'] . '=' . $msg,0);
		echo "Program failed! Please try again";
    } 
}
?>
</body>
</html>

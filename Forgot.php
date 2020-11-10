<?php
/** PAI CRUD Forgot Password
 * package    PAI_CRUD 20200406
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and sets up email token for password reset
 */
?>
<!DOCTYPE html>
<html>
<head>
<title>WTrak Menu v2.1</title>
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
	$smsg = "Enter email address to get an email with a reset password link";
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
		// check if entered email is in wuser
		$sql = "SELECT * FROM `wuser` where email = :email AND wactive = :wactive";
		$stmt = $pdo->prepare($sql);
		$email = $_POST["email"];
		$val = array("email" => $email,"wactive" => true);
		$stmt->execute($val);
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		if($res) {
			// now create token and update user record
			$url = PWToken($res['userid'],$pdo);
			if ($url) {
				// now email password token
				$to = $email;
				$subject = 'WTrak Password Reset' ;
				$message = "Click here to reset your password: " .  $url;
				$headers = 'From: cbarlow@pathfinderassociatesinc.com' . "\r\n" .
				'Reply-To: cbarlow@pathfinderassociatesinc.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
				if (mail($to, $subject, $message, $headers)) {
					header("Location:Processing.php");
				} else {
					$fmsg = "Mail failed";
				}
			}
		} 
		// return to Login
		header("Location:Processing.php");
	}
?>
<p>
<div class="container">
	<div class="row" col-xs-6 col-xs-offset-3>
     <?php if(isset($smsg)){ ?><div class="alert alert-success col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<form name="frmUser" method="post" class="form-horizontal col-xs-12 col-md-6 col-md-offset-3">
		<h2>WTrak Reset Password</h2>
			<div class="form-group">
			    <label for="email" class="col-xs-4 control-label">Email address</label>
			    <div class="col-xs-8">
			      <input type="text" name="email"  class="form-control" value="<?php echo $email; ?>" required id="email"/>
			    </div>
			</div>
			<div class="form-group">
			<input type="submit" name="SendPass" class="btn btn-primary col-xs-8 col-xs-offset-4 col-md-8 col-md-offset-4" value="Reset Password" />
			</div>
		</form>
	</div>
</div>
<!--Footer-->
<footer class="page-footer font-small blue pt-4 mt-4">
<!--Copyright-->
    <div class="footer-copyright py-3 text-center">
        Copyright © 2020 
        <a href="http://pathfinderassociatesinc.com/"> Pathfinder Associates, Inc.</a>
    </div>
<!--/.Copyright-->
</footer>
<!--/.Footer-->

<?php
function PWToken($userid,$pdo) {
	//creates token and updates user db
	// Create tokens
	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);
	// Token expiration
	$expires = new DateTime('NOW');
	$expires->add(new DateInterval('PT01H')); // 1 hour
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                $_SERVER['REQUEST_URI'];
	$link = str_replace('Forgot.php','pwreset.php?',$link);
	$url = $link . http_build_query([
		'selector' => $selector,
		'validator' => bin2hex($token)]) ;
	// now update user record
	$sql = "UPDATE `wuser` SET selector=:selector, token=:token, expires=:expires WHERE userid=:userid";
	$val = array("userid" => $userid,"selector" => $selector, "token" => hash('sha256', $token), "expires" => $expires->format('U'));
	$stmt = $pdo->prepare($sql);
	if($stmt->execute($val)){
		return $url;
	}else{
		$fmsg = "Failed to update data.";
		return false;
	}
}

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

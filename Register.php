<?php
/** PAI CRUD Add User
 * package    PAI_CRUD 20200406
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and adds to the wuser table
 *
 * Need to add in email verification sent like ForgotPW and then set Active
 */
// check if logged in 
session_start();
unset($_SESSION["wuserid"]);
register_shutdown_function('shutDownFunction');

// find the db and open it and return $pdo object
require ("DBopen.php");
require_once ('PAI_GoogleAuth.php');
$ga = new PAI_GoogleAuth();

// check if Post from submit on form below
if(isset($_POST) & !empty($_POST)){
	$scode = $_POST['scode'];
	$qrCodeUrl = $ga->getQRCodeGoogleUrl('WTrak', $scode);	
	$email = $_POST['email'];
	$username = $_POST['username'];
	$wgoal = $_POST['goal'];
	$wgoaldate = $_POST['goaldate'];
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $fmsg = "Invalid email format";
    } else {
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$sql = "INSERT INTO `wuser` (email, username, password, scode, wgoal, wgoaldate) VALUES (:email, :username, :password, :scode, :wgoal, :wgoaldate)";
		$val = array("email" => $email,"username" => $username, "password" => $password, "scode" => $scode, "wgoal" => $wgoal, "wgoaldate" => $wgoaldate);
		$stmt = $pdo->prepare($sql);
		try {
			$stmt->execute($val);
		} catch (PDOException $e) {
				if ($e->getCode() == 23000 ) {
				// duplicate email
				$fmsg = "That email address is already registered. Please <a href='Login.php'>     Login</a>";
			} else {
				$fmsg = 'Update failed: ' . $e->getCode();
			}
		}
		// now send activate email
		// now create token and update user record
		$url = ActToken($email,$pdo);
		if ($url) {
			// now email password token
			$to = $email;
			$subject = 'WTrak Activation' ;
			$message = "Click here to activate your account: " .  $url;
			$headers = 'From: cbarlow@pathfinderassociatesinc.com' . "\r\n" .
			'Reply-To: cbarlow@pathfinderassociatesinc.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			if (mail($to, $subject, $message, $headers)) {
				header("Location:Processing.php");
			} else {
				$fmsg = "Mail failed";
			}
		}

		header('location: Processing.php');
	}
} else {
	// setup defaults
	$wgoaldate = date('Y-m-d');
	//generate a secret code
	$scode = $ga->createSecret();
	$qrCodeUrl = $ga->getQRCodeGoogleUrl('WTrak', $scode);	
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
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
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
	<h2>WTrak Register</h2>
 		<form method="post" class="form-horizontal col-xs-12 col-md-6 col-md-offset-3 ">
			<div class="form-group">
			    <label for="email" class="col-xs-4 control-label">Email address</label>
			    <div class="col-xs-8">
			      <input type="text" name="email"  maxlength="40" class="form-control" id="email"  required placeholder="Email address" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="username" class="col-xs-4 control-label">Username</label>
			    <div class="col-xs-8">
			      <input type="text" name="username"  maxlength="10" class="form-control" id="username" value="<?php echo $username; ?>" required placeholder="Username" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="password" class="col-xs-4 control-label">Password</label>
			    <div class="col-xs-8">
			      <input type="password" name="password"  class="form-control" id="password" required  />
			    </div>
			</div>
			<div class="form-group">
			    <label for="goal" class="col-xs-4 control-label">Goal</label>
			    <div class="col-xs-8">
			      <input type="text" name="goal"  class="form-control" id="goal" value="<?php echo $wgoal; ?>" />
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
			      <input type="text" readonly class="form-control-plaintext" name="scode"  required id="scode" value="<?php echo $scode; ?>" required placeholder="Secret code"/>
				  <img src="<?php echo $qrCodeUrl; ?>"> 
			    </div>
			</div>

			<div class="form-group">
				<input type="submit" class="btn btn-primary col-xs-4 col-xs-offset-7" id="submit" value="Submit" name="submit" />
				<input type="cancel" class="btn btn-warning col-xs-4 col-xs-offset-7" value="cancel" onClick="window.location='Login.php';"/>
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
function ActToken($email,$pdo) {
	//creates activate token and url
	// Create tokens
	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);
	// Token expiration
	$expires = new DateTime('NOW');
	$expires->add(new DateInterval('PT01H')); // 1 hour
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                $_SERVER['REQUEST_URI'];
	$link = str_replace('Register.php','activate.php?',$link);
	$url = $link . http_build_query([
		'selector' => $selector,
		'validator' => bin2hex($token)]) ;
	// now update user record
	$sql = "UPDATE `wuser` SET selector=:selector, token=:token, expires=:expires WHERE email=:email";
	$val = array("selector" => $selector, "token" => hash('sha256', $token), "expires" => $expires->format('U'), "email" => $email);
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

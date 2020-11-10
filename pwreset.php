<?php
/** PAI CRUD Reset Password
 * package    PAI_CRUD 20200406
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and checks email token for password reset
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
	require_once ('PAI_GoogleAuth.php');
	$ga = new PAI_GoogleAuth();

	$smsg = "Enter new password and/or secret code";
	$row = "";
	// Check for tokens
	$selector = filter_input(INPUT_GET, 'selector');
	$validator = filter_input(INPUT_GET, 'validator');
	// first check if Post from this form 
	if(!empty($_POST)) {
		if (isset($_POST['Newcode'])) {
			$scode = $ga->createSecret();
			$qrCodeUrl = $ga->getQRCodeGoogleUrl('WTrak', $scode);
		} else {	
			//now reset password and delete tokens
			// Get record for these tokens
			$sql = "SELECT * FROM wuser WHERE selector = :selector AND expires >= :time";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['selector'=>$selector,'time'=>time()]);
			$r = $stmt->fetch(PDO::FETCH_ASSOC);
			//if not found then exit
			if (!$r) {header('location: Login.php');}
			// now process reset
			$auth_token = $r['token'];
			$calc = hash('sha256', hex2bin($validator));
			// Validate tokens
			if ( $calc == $auth_token )  {
	//		if ( hash_equals( $calc, $auth_token->token ) )  {
				// Update password
				$password = password_hash($_POST['pw'], PASSWORD_DEFAULT);
				$scode = $_POST['scode'];
				$sql = "UPDATE `wuser` SET password=:password, scode=:scode, selector=:selector, token=:token, expires=:expires WHERE userid=:userid";
				$val = array("password" => $password, "scode" => $scode, "selector" => null, "token" => null, "expires" => null, "userid" => $r['userid']);
				$stmt = $pdo->prepare($sql);
				if($stmt->execute($val)){
					header('location: Login.php');
				}else{
					$fmsg = "Failed to update data.";
				}
			} else {$fmsg = $r['token'];}
			}
	}else {
		// check if tokens valid
		if (!( false !== ctype_xdigit( $selector ) && false !== ctype_xdigit( $validator ))) {
			header('location: Login.php');
		} else {
			// Get record for these tokens
			$sql = "SELECT * FROM wuser WHERE selector = :selector AND expires >= :time";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['selector'=>$selector,'time'=>time()]);
			$r = $stmt->fetch(PDO::FETCH_ASSOC);
			//if not found then exit
			if (!$r) {header('location: View.php');}
			$scode = $r['scode'];
		}
	}
?>

<p>
<div class="container">
	<div class="row" col-xs-6 col-xs-offset-3>
     <?php if(isset($smsg)){ ?><div class="alert alert-success col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<form name="frmReset" method="post" class="form-horizontal col-xs-12 col-md-6 col-md-offset-3">
		<h2>WTrak Reset Password and/or Secret Code</h2>
			<div class="form-group">
			    <label for="pw" class="col-xs-4 control-label">Password</label>
			    <div class="col-xs-8">
			      <input type="password" name="pw"  class="form-control"  required id="pw"/>
			    </div>
			</div>
			<div class="form-group">
			    <label for="scode" class="col-xs-4 control-label">Secret code</label>
			    <div class="col-xs-8">
			      <input type="text" name="scode"  class="form-control" required id="scode" value="<?php echo $scode; ?>" required placeholder="Secret code"/>
				 </div>
				 <div>
				  <img src="<?php echo $ga->getQRCodeGoogleUrl('WTrak', $scode); ?>"> 
				  <input type="submit" class="btn btn-primary" id="Newcode" value="Newcode" name="Newcode" />
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


<?php
/** PAI CRUD Activate account
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
	$row = "";
	// Check for tokens
	$selector = filter_input(INPUT_GET, 'selector');
	$validator = filter_input(INPUT_GET, 'validator');
	// check if tokens valid
	if (!( false !== ctype_xdigit( $selector ) && false !== ctype_xdigit( $validator ))) {
		header('location: Login.php');
	} else {
		//now activate and delete tokens
		// Get record for these tokens
		$sql = "SELECT * FROM wuser WHERE selector = :selector AND expires >= :time";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['selector'=>$selector,'time'=>time()]);
		$r = $stmt->fetch(PDO::FETCH_ASSOC);
		//if not found then exit
		if (!$r) {header('location: Login.php');}
		// now process activate
		$auth_token = $r['token'];
		$calc = hash('sha256', hex2bin($validator));
		// Validate tokens
		if ( $calc == $auth_token )  {
//		if ( hash_equals( $calc, $auth_token->token ) )  {
			// Update active
			$sql = "UPDATE `wuser` SET wactive=:wactive, selector=:selector, token=:token, expires=:expires WHERE userid=:userid";
			$val = array("wactive" => true, "selector" => null, "token" => null, "expires" => null, "userid" => $r['userid']);
			$stmt = $pdo->prepare($sql);
			if($stmt->execute($val)){
				header('location: Login.php');
			}else{
				$fmsg = "Failed to update data.";
			}
		} else {$fmsg = $r['token'];}
	}
echo $fmsg;
?>

<p>
<div class="container">
	<div class="row" col-xs-6 col-xs-offset-3>
		<h2>WTrak Activate Account</h2>
		<p><p><p>
		Your account failed to activate. Please contact the administrator
		<p>
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


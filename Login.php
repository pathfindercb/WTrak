<?php
/** PAI CRUD Login
 * package    PAI_CRUD 202001104
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and sets userid in session for login
 */
?>
<!DOCTYPE html>

<!-- Menu 03/26/2020 -->
<!--	This is the main web index for all the CRUD file maintenance using a form to select-->

<html>
<head>
<title>WTrak Menu v2.2</title>
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
	$dbug = false;
	session_start();
	unset($_SESSION["wuserid"]);
	register_shutdown_function('shutDownFunction');
	require ("DBopen.php");
	if ($dbug) {echo ($pfolder . " " . $dsn);}
	include ("PAI_crypt.class.php");
	//get the secret key not stored in www folders
	require_once ($pfolder . 'DBkey.php');
	$paicrypt = new PAI_crypt($DBkey);
	require_once ('PAI_GoogleAuth.php');
	$ga = new PAI_GoogleAuth();
	$smsg = "Please Login. Click here to <a href='Register.php'>Register</a>";
	$row = "";
	//get cookie then unset
	if(isset($_COOKIE["wemail"])) {
		$email = $_COOKIE["wemail"];
		unset($_COOKIE["wemail"]);
	} else {
		$email = "";
	}
	// first check Post from Login form 
	if(!empty($_POST)) {
		// check if entered userid is in wuser
		$sql = "SELECT * FROM `wuser` where email = :email AND wactive = :wactive";
		$stmt = $pdo->prepare($sql);
		$email = $_POST["email"];
		$scode = $_POST["scode"];
		$val = array("email" => $email,"wactive" => true);
		$stmt->execute($val);
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		if($res) {
			// now check password
			if (password_verify($_POST['password'],$res['password']) && $ga->verifyCode($res['scode'], $_POST["scode"], 2)) {
				$_SESSION["wemail"] = $res['email'];
				$_SESSION["wusername"] = $res['username'];
				$_SESSION["wuserid"] = $res['userid'];
				$_SESSION["wgoal"] = $res['wgoal'];
				$_SESSION["wgoaldate"] = $res['wgoaldate'];
				//set cookie for a month if Remember me is checked
				if (isset($_POST['chkcookie'])) {
					setcookie("wemail", $res['email'], time() + (86400 * 30), "/");
				}
				header("Location:View.php");
			} else {
				$fmsg = "Invalid Email or Password or Secret Code!" ;
			}
		} else {
			$fmsg = "Invalid Email or Password or Secret Code!" ;

		}
	}
?>
<p>
<div class="container">
	<div class="row" col-xs-6 col-xs-offset-3>
     <?php if(isset($smsg)){ ?><div class="alert alert-success col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<form name="frmUser" method="post" class="form-horizontal col-xs-12 col-md-6 col-md-offset-3">
		<h2>WTrak Login</h2>
			<div class="form-group">
			    <label for="email" class="col-xs-4 control-label">Email address</label>
			    <div class="col-xs-8">
			      <input type="text" name="email"  class="form-control" value="<?php echo $email; ?>" required id="email"/>
			    </div>
			</div>
			<div class="form-group">
			    <label for="password" class="col-xs-4 control-label">Password</label>
			    <div class="col-xs-8">
			      <input type="password" name="password"  class="form-control" required id="password"/>
			    </div>
			</div>
			<div class="form-group">
			    <label for="scode" class="col-xs-4 control-label">Secret code</label>
			    <div class="col-xs-8">
			      <input type="number" name="scode"  class="form-control" required id="scode"/>
			    </div>
			</div>
			<div class="form-group">
			<input type="submit" name="Login" class="btn btn-primary col-xs-8 col-xs-offset-4 col-md-8 col-md-offset-4" value="Login" />
			</div>
			<div class="clearfix col-xs-8 col-xs-offset-4 col-md-8 col-md-offset-4">
				<label class="float-left checkbox-inline"><input type="checkbox" name="chkcookie" value= "Yes" checked> Remember me</label>
				<a href="Forgot.php" class="float-right">Forgot Password?</a>
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

<?php
/** PAI CRUD Add User
 * package    PAI_CRUD 20180513
 * @license   Copyright © 2018 Pathfinder Associates, Inc.
 *	opens the wtrak db and adds to the wuser table
 */
// check if logged in 
session_start();

// find the db and open it and return $pdo object
require ("DBopen.php");
// setup encryption
include ("PAI_crypt.class.php");
//get the secret key not stored in www folders
require_once ($pfolder . 'DBkey.php');
$paicrypt = new PAI_crypt($DBkey);

// check if Post from submit on form below
if(isset($_POST) & !empty($_POST)){
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $paicrypt->encrypt($_POST['password']);
	$wgoal = $_POST['goal'];
	$wgoaldate = $_POST['goaldate'];
	$sql = "INSERT INTO `wuser` (email, username, password, wgoal, wgoaldate) VALUES (:email, :username, :password, :wgoal, :wgoaldate)";
	$val = array("email" => $email,"username" => $username, "password" => $password, "wgoal" => $wgoal, "wgoaldate" => $wgoaldate);
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
			      <input type="text" name="username"  maxlength="10" class="form-control" id="username"  required placeholder="Username" />
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
			      <input type="text" name="goal"  class="form-control" id="goal"  />
			    </div>
			</div>
			<div class="form-group">
			    <label for="goaldate" class="col-xs-4 control-label">Goal date</label>
			    <div class="col-xs-8">
			      <input type="date" name="goaldate"  class="form-control" id="goaldate" value="<?php echo date('Y-m-d'); ?>" required placeholder="Goal Date" />
			    </div>
			</div>

			<div class="form-group">
				<input type="submit" class="btn btn-primary col-xs-4 col-xs-offset-7" id="submit" value="Submit" name="submit" />
				<input type="cancel" class="btn btn-warning col-xs-4 col-xs-offset-7" value="cancel" onClick="window.location='Login.php';"/>
			</div>
		</form>
	</div>
</div>

</body>
</html>
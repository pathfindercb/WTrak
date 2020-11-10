<?php
/** PAI CRUD Create
 * package    PAI_CRUD 20201030
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and add to the wdata table
 */

// check if logged in 
session_start();
if(!isset($_SESSION["wuserid"])) {
	header("Location:Login.php");
	exit;
}

require ("DBopen.php");
include ("PAI_crypt.class.php");
//get the secret key not stored in www folders
require_once ($pfolder . 'DBkey.php');
$paicrypt = new PAI_crypt($DBkey);

if(isset($_POST) & !empty($_POST)){
	$wdate = ($_POST['wdate']);
	$wgt = ($_POST['wgt']);
	$wnote = $paicrypt->encrypt($_POST['wnote']);

	$sql = "INSERT INTO `wdata` (userid, wdate, wgt, wnote) VALUES (:userid,:wdate, :wgt, :wnote)";
	$val = array("userid" => $_SESSION["wuserid"], "wdate" => $wdate, "wgt" => $wgt, "wnote" => $wnote);
	$stmt = $pdo->prepare($sql);
	if($stmt->execute($val)){
		header('location: View.php');
	}else{
		$fmsg = "Failed to update data.";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Weight</title>
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
      <?php if(isset($smsg)){ ?><div class="alert alert-success col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger col-xs-12 col-md-6 col-md-offset-3" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<form method="post" class="form-horizontal col-xs-12 col-md-6 col-md-offset-3">
		<h2>Create Weight</h2>
			<div class="form-group">
			    <label for="wdate" class="col-sm-2 control-label">Date</label>
			    <div class="col-sm-6">
			    <input type="date" name="wdate"  class="form-control" id="wdate" value="<?php echo date('Y-m-d'); ?>" placeholder="Date" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="wgt" class="col-sm-2 control-label">Weight</label>
			    <div class="col-sm-6">
			    <input type="number" step="0.1"  name="wgt"  class="form-control" id="wgt"  placeholder="Weight" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="wnote" class="col-sm-2 control-label">Note</label>
			    <div class="col-sm-6">
			    <input type="text" name="wnote" maxlength="40" class="form-control" id="wnote"  placeholder="Note" />
			    </div>
			</div>
	  <div class="form-group">
				<input type="submit" class="btn btn-primary col-sm-2 col-sm-offset-6" value="submit" />
				<input type="cancel" class="btn btn-warning col-sm-2 col-sm-offset-6" value="cancel" onClick="window.location='View.php';"/>
			</div>
			
		</form>
	</div>
</div>
</body>
</html>
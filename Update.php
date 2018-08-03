<?php
/** PAI CRUD View
 * package    PAI_CRUD 20180511
 * @license   Copyright © 2018 Pathfinder Associates, Inc.
 *	opens the wtrak db and view the wdata table
 */

// check if logged in 
session_start();
if(!isset($_SESSION["wuserid"])) {
	header("Location:Login.php");
}
require ("DBopen.php");

// find the record
$dataid = $_GET['dataid'];
$sql = "SELECT * FROM `wdata` WHERE dataid=:dataid";
$stmt = $pdo->prepare($sql);
$stmt->execute([$dataid]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);

// check if Submit clicked
if(isset($_POST) & !empty($_POST)){
	$wgt = ($_POST['wgt']);
	$wdate = ($_POST['wdate']);
	$wnote = ($_POST['wnote']);

	$sql = "UPDATE `wdata` SET wgt=:wgt, wdate=:wdate, wnote=:wnote WHERE dataid=:dataid";
	$val = array("wdate" => $wdate, "wgt" => $wgt, "wnote" => $wnote, "dataid" => $dataid);
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
	<title>Update WTrak</title>
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
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<form method="post" class="form-horizontal col-xs-12 col-md-6 col-md-offset-3">
		<h2>Update WTrak</h2>
			<div class="form-group">
			    <label for="wdate" class="col-sm-2 control-label">Date</label>
			    <div class="col-sm-6">
			    <input type="date" name="wdate"  class="form-control" id="wdate" value="<?php echo $r['wdate']; ?>" placeholder="Date" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="wgt" class="col-sm-2 control-label">Weight</label>
			    <div class="col-sm-6">
			    <input type="text" name="wgt"  class="form-control" id="wgt" value="<?php echo $r['wgt']; ?>"  placeholder="Weight" />
			    </div>
			</div>
			<div class="form-group">
			    <label for="wnote" class="col-sm-2 control-label">Weight</label>
			    <div class="col-sm-6">
			    <input type="text" name="wnote" maxlength="40"  class="form-control" id="wnote" value="<?php echo $r['wnote']; ?>"  placeholder="Note" />
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
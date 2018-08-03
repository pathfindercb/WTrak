<?php
/** PAI CRUD Import
 * package    PAI_CRUD 20180513
 * @license   Copyright © 2018 Pathfinder Associates, Inc.
 *	opens the wtrak db and imports to the wdata table
 */

// check if logged in 
session_start();
if(!isset($_SESSION["wuserid"])) {
	header("Location:Login.php");
}
require ("DBopen.php");
// check if Post from submit on form below
if(isset($_POST) & !empty($_POST)){

	// called by Profile to upload the file and check for format/size & load to array
		if($_FILES["import"]["error"] > 0){
			$fmsg =  "Error: " . $_FILES["import"]["error"] . "<br>";
			$fmsg .=  "Error on upload. Please click the left menu item to re-run";
			return false;
		} 
		// get file info
		$filename = $_FILES["import"]["name"];
		$filetype = $_FILES["import"]["type"];
		$filesize = $_FILES["import"]["size"];
		$tempfile = $_FILES["import"]["tmp_name"];
		// Verify file extension and read file to array
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(($ext == "csv")) {
			/* Map Rows and Loop Through Them */
			$rows   = array_map('str_getcsv', file($tempfile));
			$header = array_shift($rows);
			$dbWgt    = array();
			foreach($rows as $row) {
				$dbWgt[] = array_combine($header, $row);
			}
			// check if file has correct format then import
			if (!(count($dbWgt[0])==3)) {
				$fmsg =  "Error: " . count($dbWgt[0]) . " Not valid CSV file.";
				unset ($dbWgt);
			}
		} elseif(($ext == "json")) {
			$json = file_get_contents($tempfile);
			$response = json_decode($json,true); // decode the JSON into an associative array
			$dbWgt = $response['Response']['User']['Data'];
		} else {
			$fmsg =  $filename . " Error: Please select a proper file format.";
		}
		if (isset($dbWgt)) {
			foreach ($dbWgt as $row) {
				$wdate = ($row['date']);
				$wgt = ($row['weight']);
				$wnote = ($row['note']);
				$sql = "INSERT INTO `wdata` (userid, wdate, wgt, wnote) VALUES (:userid,:wdate, :wgt, :wnote)";
				$val = array("userid" => $_SESSION["wuserid"], "wdate" => $wdate, "wgt" => $wgt, "wnote" => $wnote);
				$stmt = $pdo->prepare($sql);
				$stmt->execute($val);
			}
			header('location: View.php');
			
		}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Import WTrak</title>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
 
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
 
<link rel="stylesheet" href="styles.css" >

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">

<script type='text/javascript'>

    function showFileModified() {
        var input, file;

        // Testing for 'function' is more specific and correct, but doesn't work with Safari 6.x
        if (typeof window.FileReader !== 'function' &&
            typeof window.FileReader !== 'object') {
            write("The file API isn't supported on this browser yet.");
            return;
        }

        input = document.getElementById('fileSelect');
        if (!input) {
            write("Um, couldn't find the filename element.");
        }
        else if (!input.files) {
            write("This browser doesn't seem to support the `files` property of file inputs.");
        }
        else if (!input.files[0]) {
            write("Please select a file before clicking 'Submit'");
        }
        else {
            file = input.files[0];
 			document.getElementById('fileTime').value = file.lastModified;
        }

    }

</script>
</head>
<body>
<div class="container">
	<div class="row">
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<form name="frmReport" method="post"  enctype="multipart/form-data" class="form-horizontal col-xs-12">
		<h2>Import</h2>
		<h4>Use this form to import CSV file or JSON file of weight data</h4>
			<div class="form-group">
			<label for="input1" class="col-sm-2 control-label">Import type</label>
			<div class="col-sm-6">
				<select name="choice" class="form-control">
				<option value="1">CSV with header: date,weight,note</option>
				<option value="2">JSON in format: {Response}{version}{Data}{Date}{Weight}{Note}</option>
				</select>			
			</div>
			</div>
			<div class="form-group">
			<div class="col-sm-6">
				<input type="file" name="import" id="fileSelect" onchange="showFileModified();">
				<input type="hidden" name="fileTime" id="fileTime">
				<input type="submit" class="btn btn-primary col-xs-4 col-xs-offset-7" id="submit" value="Import" name="submit" />
				<input type="cancel" class="btn btn-warning col-xs-4 col-xs-offset-7" value="cancel" onClick="window.location='Profile.php';"/>
			</div>
			</div>
		</form>
</body>
</html>

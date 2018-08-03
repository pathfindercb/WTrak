<?php
/** PAI CRUD Create
 * package    PAI_CRUD 20180502
 * @license   Copyright © 2018 Pathfinder Associates, Inc.
 *	opens the wtrak db and add to the wdata table
 *	the generic version can accept a table and get the columns but this hardcodes wtrak
 */

// check if logged in 
register_shutdown_function('shutDownFunction');
session_start();
if(!isset($_SESSION["wuserid"])) {
	header("Location:Login.php");
}

require ("DBopen.php");


/*if (isset($_GET['sql'])) {
	$sql = $_GET['sql'];
	
 } elseif (isset($_GET['mtable'])){
	$mtable = $_GET['mtable'];
	$q = $pdo->prepare("DESCRIBE " . $mtable);
	$q->execute();
	$tfields = $q->fetchAll(PDO::FETCH_COLUMN);
	//header flipped set to strings
	$keys = array_keys(array_flip($tfields));
	$hdr = array_fill_keys($keys, "string");

	//print("<pre>".print_r($tfields,true)."</pre>");		

	$sql = "SELECT * FROM " . $mtable . " where userid = '" . $_SESSION['userid'] . "' ORDER BY " . $tfields[0];
	
} else {
	header("Location:View.php");
 }
 */
 
$sql = "SELECT a.username, a.email, b.wdate, b.wgt, b.wgt - a.wgoal, b.wnote FROM `wdata` b JOIN wuser a ON a.userid = b.userid WHERE b.userid = '" . $_SESSION['wuserid'] . "' ORDER BY wdate ";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$r = $stmt->fetchALL(PDO::FETCH_ASSOC);
//header flipped set to strings
$keys = array_keys(array_flip(array("Username","Email address", "Date", "Weight", "To Goal", "Notes")));
$hdr = array_fill_keys($keys, "string");

//print("<pre>".print_r($r,true)."</pre>");		

	//Creates the XL file from all the array
	// Include the required Class file
	include('PAI_xlsxwriter.class.php');
	$filename = "WTrak" . date('Ymd') . ".xlsx";
	header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
		//setup body & heading row style
	$bstyle = array( 'font'=>'Arial','font-size'=>10,'font-style'=>'normal', 'halign'=>'center', 'border'=>'bottom');
	$hstyle = array( 'font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'halign'=>'center', 'border'=>'bottom');
	$h1style = array( 'font'=>'Arial','font-size'=>12,'font-style'=>'bold', 'halign'=>'left', 'border'=>'bottom');
	$hdrRes = array(date('m/d/y'),'WTrak Listing');
	//write header then sheet data and output file
	$writer = new XLSXWriter();
	$writer->setTitle('WTrak Listing v1.0');
	$writer->setAuthor('Chris Barlow, Pathfinder Associates, Inc. ');
	$writer->setColWidths("WTrak",array(15,30,10,10,10,40,20,20,20,20,20));
	$writer->writeSheetHeader("WTrak",$hdr,true);
	$writer->writeSheetRow("WTrak",$keys,$hstyle);
	$writer->writeSheet($r,"WTrak",$hdr,true);
	$writer->writeToStdOut(); 
	unset($writer);
exit;


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
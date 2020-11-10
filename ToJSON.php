<?php
/** PAI CRUD Create
 * package    PAI_CRUD 20201030
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and add to the wdata table
 *	the generic version can accept a table and get the columns but this hardcodes wtrak
 */

// check if logged in 
register_shutdown_function('shutDownFunction');
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

$sql = "SELECT wdate as date, wgt as weight, wnote as note FROM `wdata` where userid = " . $_SESSION['wuserid'] . " ORDER BY wdate";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$r = $stmt->fetchALL(PDO::FETCH_ASSOC);
foreach ($r as &$row) {
	$row[note] = $paicrypt->decrypt($row[note]);
}

// build a user array with wuser info and a data array with all date,weight data
	$response = array ('Response' => array ( 
		'Title' => 'WTrak', 
		'Version' => "2.0", 
		'Exported' => date('Y-m-d'),
		'Copyright' => 'Copyright 2020 Pathfinder Associates, Inc.',
		'Author' => 'Chris Barlow & Joe Maffei',
		'User' => array (
			'Email' => $_SESSION['wemail'],
			'Username' => $_SESSION['wusername'],
			'Goal' => $_SESSION['wgoal'],
			'Goaldate' => $_SESSION['wgoaldate'],
			'Data' => $r 
			)
	));
// write out file
	$filename = "WTrak" . date('Ymd') . ".json";
	header('Content-disposition: attachment; filename="'. $filename .'"');
	header('Content-Type: application/json');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	echo json_encode($response);
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
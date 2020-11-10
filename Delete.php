<?php
/** PAI CRUD Create
 * package    PAI_CRUD 20201030
 * @license   Copyright © 2018 Pathfinder Associates, Inc.
 *	opens the wtrak db and add to the wdata table
 */

// check if logged in 
session_start();
if(!isset($_SESSION["wuserid"])) {
	header("Location:Logon.php");
	exit;
}

require ("DBopen.php");

	$dataid = $_GET['dataid'];
	$DelSql = "DELETE FROM `wdata` WHERE dataid=:dataid";
	$res = $pdo->prepare($DelSql);
	if($res->execute([$dataid])){
		header('location: View.php');
	}else{
		echo "Failed to delete";
	}
?>
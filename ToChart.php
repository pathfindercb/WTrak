<?php
/** PAI CRUD Create
 * package    PAI_CRUD 20180502
 * @license   Copyright © 2018 Pathfinder Associates, Inc.
 *	opens the wtrak db and add to the wdata table
 */

// check if logged in 
session_start();
if(!isset($_SESSION["wuserid"])) {
	header("Location:Logon.php");
}

echo "This feature will be in version 2.0 - coming soon!"
?>
<?php
/** PAI dbopen include file
 * package    PAI_CRUD 20180512
 * @license   Copyright © 2018 Pathfinder Associates, Inc.
 *	opens the db based on location shown in DBFolder
 *	required by CRUD programs to open the database
 */

	// first include file containing host, db, user, password so not in www folder
	if (file_exists("DBfolder.php")) {include ("DBfolder.php");}
	if (!isset($pfolder)) {$pfolder="";}
	require ($pfolder . 'DBconnect.php');
	$charset = 'utf8';
	$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
	$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	$pdo = new PDO($dsn, $user, $pass, $opt);
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
?>
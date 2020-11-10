<?php
/** PAI CRUD View
 * package    PAI_CRUD 20201104
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and view the wdata table
 */

// check if logged in 
session_start();
if(!isset($_SESSION['wuserid'])) {
	header("Location:Login.php");
	exit;
}
require ("DBopen.php");
include ("PAI_crypt.class.php");
//get the secret key not stored in www folders
require_once ($pfolder . 'DBkey.php');
$paicrypt = new PAI_crypt($DBkey);

$now = time();
$gate = $now; 
if(isset($_SESSION["wgoaldate"])) {$gdate = strtotime($_SESSION["wgoaldate"]);}
$datediff = $gdate - $now;
$days = round($datediff / (60 * 60 * 24));
$fmsg = "";
if (isset($_SESSION['wgoal'])) {
	$smsg = $fmsg . "Welcome <a href='Profile.php'>" . $_SESSION['wusername'] . "</a>. Your goal is " . $_SESSION['wgoal'] . " by " . $_SESSION['wgoaldate']. " in " . ceil($days/7) . " weeks.   <a href='Logout.php'>     Logout</a>";
}
$perpage = 15; 
if(isset($_GET['page']) & !empty($_GET['page'])){
	$curpage = $_GET['page'];
}else{
	$curpage = 1;
}
$start = ($curpage * $perpage) - $perpage ;
$PageSql = "SELECT COUNT(*) FROM `wdata` where userid = '" . $_SESSION['wuserid'] ."'";
$pageres = $pdo->prepare($PageSql);
$pageres->execute();
$totalres = $pageres->fetchColumn();
$endpage = ceil($totalres/$perpage);
$startpage = 1;
if ($endpage > 0) {
	$nextpage = $curpage + 1;
	$previouspage = $curpage - 1;
} else {
	$nextpage = "";
}

$sql = "SELECT dataid, wdate, wgt, wnote FROM `wdata` where userid = '" . $_SESSION['wuserid'] . "' ORDER BY wdate desc LIMIT $start, $perpage";
$stmt = $pdo->prepare($sql);
$stmt->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<title>View WTrak</title>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
 
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
 
<link rel="stylesheet" href="styles.css" >

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
	<div class="row">
     <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
	<h2>View WTrak 2.1</h2>
		<input type="button" class="btn btn-info" value="Add Weight" onclick="location.href = 'Create.php';">
		<input type="button" class="btn btn-info" value="Excel" onclick="location.href = 'ToExcel.php';">
		<input type="button" class="btn btn-info" value="JSON" onclick="location.href = 'ToJSON.php';">
		<input type="button" class="btn btn-info" value="Chart" onclick="location.href = 'ToChart.php';">
		<table class="table "> 
		<thead> 
			<tr> 
				<th>Date</th> 
				<th>Weight</th> 
				<th>To Goal</th> 
				<th>Notes</th>
				<th>Actions</th>
			</tr> 
		</thead> 
		<tbody> 
		<?php 
		while($r = $stmt->fetch(PDO::FETCH_ASSOC)){
		?>
			<tr> 
				<th scope="row"><?php echo $r['wdate']; ?></th> 
				<td><?php echo $r['wgt']; ?></td> 
				<td><?php echo $r['wgt']-$_SESSION['wgoal']; ?></td> 
				<td><?php echo $paicrypt->decrypt($r['wnote']); ?></td> 
				<td>
					<a href="Update.php?dataid=<?php echo $r['dataid']; ?>"><button type="button" class="btn btn-info btn-xs" >Edit</button></a>
					<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal<?php echo $r['dataid']; ?>">Delete</button>

					<!-- Modal -->
					  <div class="modal fade" id="myModal<?php echo $r['dataid']; ?>" role="dialog">
					    <div class="modal-dialog">
					    
					      <!-- Modal content-->
					      <div class="modal-content">
					        <div class="modal-header">
					          <button type="button" class="close" data-dismiss="modal">&times;</button>
					          <h4 class="modal-title">Delete File</h4>
					        </div>
					        <div class="modal-body">
					          <p>Are you sure?</p>
					        </div>
					        <div class="modal-footer">
					          <a href="Delete.php?dataid=<?php echo $r['dataid']; ?>"><button type="button" class="btn btn-danger">Delete</button></a>
					          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					        </div>
					      </div>
					      
					    </div>
				</td>
			</tr> 
		<?php } ?>
		</tbody> 
		</table>
	</div>

<nav aria-label="Page navigation">
  <ul class="pagination">
  <?php if($curpage != $startpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=<?php echo $startpage ?>" tabindex="-1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">First</span>
      </a>
    </li>
    <?php } ?>
    <?php if($curpage >= 2){ ?>
    <li class="page-item"><a class="page-link" href="?page=<?php echo $previouspage ?>"><?php echo $previouspage ?></a></li>
    <?php } ?>
    <li class="page-item active"><a class="page-link" href="?page=<?php echo $curpage ?>"><?php echo $curpage ?></a></li>
    <?php if($curpage != $endpage){ ?>
    <li class="page-item"><a class="page-link" href="?page=<?php echo $nextpage ?>"><?php echo $nextpage ?></a></li>
    <li class="page-item">
      <a class="page-link" href="?page=<?php echo $endpage ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Last</span>
      </a>
    </li>
    <?php } ?>
  </ul>
</nav>
</div>

</body>
</html>
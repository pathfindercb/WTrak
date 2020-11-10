<?php
/** PAI CRUD Create
 * package    PAI_CRUD 20201106
 * @license   Copyright © 2020 Pathfinder Associates, Inc.
 *	opens the wtrak db and add to the wdata table
 */

// check if logged in 
session_start();
if(!isset($_SESSION["wuserid"])) {
	header("Location:Logon.php");
	exit;
}

echo "This basic feature will be enhanced in version 3.0!";
echo " <p>  Back to <a href='View.php'>View</a>";

?>

<html>
<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"> </script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['line','corechart', 'controls']});
      google.charts.setOnLoadCallback(drawDashboard);


<?php
require ("DBopen.php");
include ("PAI_crypt.class.php");
//get the secret key not stored in www folders
require_once ($pfolder . 'DBkey.php');
$paicrypt = new PAI_crypt($DBkey);

$sql = "SELECT wdate as day , wgt as weight FROM `wdata` 
where userid = " . $_SESSION['wuserid'] . "  ORDER BY wdate";

$stmt = $pdo->prepare($sql);
$stmt->execute();

?>

function drawDashboard() {
    var data = new google.visualization.DataTable();

    data.addColumn('date', 'Date');
    data.addColumn('number', 'Weight');
    data.addRows([

<?php
while ($row = $stmt->fetch()) {
	echo  "[" . "new Date('" .  $row['day']    .  "'),"  .
		$row['weight'] .  "], "  ;
}
?>

        ]);
	// Create a dashboard.
        var dashboard = new google.visualization.Dashboard(
            document.getElementById('dashboard_div'));

        // Create a range slider, passing some options
        var donutRangeSlider = new google.visualization.ControlWrapper({
          'controlType': 'DateRangeFilter',
          'containerId': 'filter_div',
          'options': {
            'filterColumnLabel': 'Date'
          }
        });
		
		// Create a line chart, passing some options
		var lineChart = new google.visualization.ChartWrapper({
			'chartType': 'LineChart',
			'containerId': 'chart_div',
			'options': {
				'height': 500,
				'hAxis': {title: 'Date'},
				'vAxis': {title: 'Pounds'}			  
			}
		  });
		  
        // Establish dependencies, declaring that 'filter' drives 'lineChart',
        // so that the line chart will only display entries that are let through
        // given the chosen slider range.
        dashboard.bind(donutRangeSlider, lineChart);

        // Draw the dashboard.
        dashboard.draw(data);		


      }
</script>
</head>
<body>

<!--Div that will hold the dashboard-->
    <div id="dashboard_div">
      <!--Divs that will hold each control and chart-->
      <div id="filter_div"></div>
      <div id="chart_div"></div>
    </div>
	
</body>
</html>

<?php
require_once("includes/init.php"); 
//ini_set('display_errors', 0);
$dm = new DataManager();

if (isset($_GET['delete-entry'])):
	$strSQL = 'DELETE FROM log WHERE log_id =' . mysqli_real_escape_string($dm->connection, $_GET['delete-entry']);
	$result = $dm->queryRecords($strSQL);
endif;

if (isset($_GET['clear-all'])):
	if ($_GET['clear-all']):
		$strSQL = 'DELETE FROM log WHERE 1=1';
		$result = $dm->queryRecords($strSQL);
	endif;
endif;

	?>
<style>
.debugTable{
	padding:5px;
	border: 1px solid #000;
	border-collapse: collapse;
}
.debugTable th {
	background:#ccc;
	padding:5px;
	border: 1px solid #000;
}
.debugTable td {
	padding:5px;
	border: 1px solid #000;
}

</style>
<h1>Debug Log</h1>
<table width="400px">
  <tr><td><a href='debug_log.php'>&raquo; Refresh</a></td>
    <td width="50%"><a href='debug_log.php?clear-all=true'>&raquo; Clear All</a></td>
  </tr>
</table>

	<table class="debugTable">
	<thead>
	<tr>
		<th>Time</th><th>Content</th><th>User</th><th>Delete</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$strSQL = 'SELECT * FROM log ORDER BY log_time DESC';
	
	$result = $dm->queryRecords($strSQL);
	$num_rows = mysqli_num_rows($result);
			
	if ($num_rows != 0):
	
		while ($line = mysqli_fetch_assoc($result)):
			echo "<tr style='vertical-align:top'><td style='vertical-align:top'>".$line['log_time'] . "</td><td><pre>" . $line['log_val'] . "</pre></td><td style='vertical-align:top'>" . $line['log_user'] . "</td><td align='center' style='vertical-align:top'><a href='debug_log.php?delete-entry=" . $line['log_id'] . "'>X</a></td></tr>";
		endwhile;
	else:
		echo "<tr><td colspan='4'>No records</td></tr>";
	endif;		
?>
	</tbody>
	</table>
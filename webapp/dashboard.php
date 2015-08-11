<?php 
$page_id = basename(__FILE__);
require("includes/init.php"); 
require(INCLUDES . "acl_module.php"); 
$activeMenuItem = "Home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php  include(HEAD);  ?>
    <title>Dashboard | <?php echo $appConfig["app_title"]; ?></title>

</head>
<body>

<?php require(INCLUDES . "navbar.php");?>

<div class="main">
    <div class="container">
		<div class="row">
			<div class="row">
			<div class="col-md-12">	  
				  <?php  include(INCLUDES . "system_messaging.php");  ?>
			</div>
		</div>
			
			<div class="col-md-6">
				<div class="widget widget-table action-table">
						
					<div class="widget-header">
						<i class="fa fa-th-list"></i>
						<h3>Most recent orders</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th></th>
									<th>Club</th>
									<th>Value</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
							<?php 
			$dm = new DataManager();
			$query="SELECT club_name, order_date_created, order_total, order_id FROM orders
			LEFT JOIN club ON orders.order_club_id = club.club_id
			WHERE orders.is_active = 'Y'
			ORDER BY `order_date_created` DESC LIMIT 5";
			$result = $dm->queryRecords($query);
			if($result){
			while ($row = mysqli_fetch_array($result))
				{
					echo '
					<tr>
						<td><a href="orders_edit.php?id=' . $row['order_id'] . '"><i class="fa fa-edit"></i></a></td>
						<td>' . $row['club_name'] . '</td>
						<td>$' . number_format($row['order_total'],2) . '</td>
						<td>' . substr($row['order_date_created'],0,10) . '</td>						
					</tr>
					';
				}
			}
							?>
								</tbody>
							</table>
						
					</div>
					
				</div>
			</div>
			
			<div class="col-md-6">
			<div class="widget widget-table action-table">
						
					<div class="widget-header">
						<i class="fa fa-th-list"></i>
						<h3>Follow up</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Club</th>
									<th>Note</th>
									<th style="white-space: nowrap;">Follow up date</th>
								</tr>
							</thead>
							<tbody>
							<?php 
			$dm = new DataManager();
			$query="SELECT * FROM clubNotes
			LEFT JOIN club ON clubNotes.clubNotes_club_id = club.club_id
			WHERE clubNotes_followup_date != '000-00-00' AND clubNotes_followup_complete != 'Y'
			ORDER BY `clubNotes_followup_date` ASC ";
			$result = $dm->queryRecords($query);
			if($result){
			while ($row = mysqli_fetch_array($result))
				{
					echo '
					<tr>
						<td><a href="club_edit.php?id=' .$row['club_id'] . '">' . $row['club_name'] . '</a></td>
						<td><a href="clubNotes_edit.php?id=' .$row['clubNotes_id'] . '">' . $row['clubNotes_content'] . '</a></td>
						<td>' . $row['clubNotes_followup_date'] . '</td>
					</tr>
					';
				}
			}
							?>
								</tbody>
							</table>
						
					</div>
					
				</div>
				<br>
				<div class="widget widget-table action-table">
						
					<div class="widget-header">
						<i class="fa fa-th-list"></i>
						<h3>Most recent club logins</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Club</th>
									<th>Date/Time</th>
								</tr>
							</thead>
							<tbody>
							<?php 
			$dm = new DataManager();
			$query="SELECT club_name, user_login FROM user
			LEFT JOIN club ON user.user_id = club.club_user_id
			WHERE user_role = 3
			ORDER BY `user_login` DESC LIMIT 5";
			$result = $dm->queryRecords($query);
			if($result){
			while ($row = mysqli_fetch_array($result))
				{
					echo '
					<tr>
						<td>' . $row['club_name'] . '</td>
						<td>' . $row['user_login'] . '</td>

					</tr>
					';
				}
			}
							?>
								</tbody>
							</table>
						
					</div>
					
				</div>
			</div>				
		</div>
	</div>
</div> 
    
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST);?>

</body>
</html>
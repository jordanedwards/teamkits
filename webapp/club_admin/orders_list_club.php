<?php 
require("../includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "/class_user.php"); 
include(CLASSES . "/class_orders.php");
include(CLASSES . "/class_club.php"); 
$club = new Club();

$activeMenuItem = "Orders";

// Look up which club is associated with this user, then create club object
$club->get_by_user_id($currentUser->get_id()); 

?><!DOCTYPE html>
<html lang="en">
  <head>
	<?php include(HEAD); ?>
    <title><?php  echo $appConfig["app_title"];  ?> | Orders List</title>
  </head>
  <body>

<?php  require(INCLUDES . "navbar.php");  ?>

<div class="main">
  <div class="container">
  
    <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>
        <h1>Orders List</h1>
		</div>
	</div>
		
      <div class="row">
        <div class="col-md-12">
		 <p><i class="fa fa-plus-circle"></i> <a href="orders_edit.php?id=0">Create New Order</a></p>
<?php 

$dm = new DataManager();

	$s_id = escaped_var_from_post("s_id");
	$s_customer = escaped_var_from_post("s_customer");
	$s_item = escaped_var_from_post("s_item");
	$s_quantity = escaped_var_from_post("s_quantity");
	$s_status = escaped_var_from_post("s_status");
	$s_sort = escaped_var_from_post('sort');
	$s_sort_dir = escaped_var_from_post('sort_dir');
	
	if ($s_sort == ""){
		// if no sort is set, pick a default
		$s_sort = "order_id";
		$s_sort_dir = "desc";	
	}
	
	$order = " ORDER BY " . $s_sort . " " . $s_sort_dir;		
					
 ?>
        <div id="search">
          <form action="<?php  echo $_SERVER["../PHP_SELF"]  ?>?reload=true" method="post" name="frmFilter" id="frmFilter">
            <table class="admin_table" style="display:block">
              <tr>
			  <td>Order Id</td><td>Orders Status</td><td><input type="button" class="clear" value="Clear" /></td>
              </tr>
			  
              <tr>
			  <td><input type="text" name="s_id"  value="<?php  echo $s_id  ?>"/></td>
				<td><input type="text" name="s_club_id"  value="<?php  echo $s_club_id  ?>"/></td>
				<td><input type="text" name="s_status"  value="<?php  echo $s_status  ?>"/></td>
				<input type="hidden" id="sort" name="sort" value="<?php echo $sort?>" />
				<input type="hidden" id="sort_dir" name="sort_dir" value="<?php echo $sort_dir?>" />
								
                <td valign="top"><input type="submit" class="submit" value="Search" /></td>
              </tr>
            </table>
          </form>
        </div>
			<br>
<?php 
					
					$query = $session->getQuery($_SERVER["PHP_SELF"]);
					$reload = (isset($_GET['reload']) && $_GET['reload'] == "true" && isset($_GET['page']) == false ? $_GET['reload'] : "");
					
					if ($query == "" || $reload == "true") {
					// Page set to reload (new query)		
							 
						if($s_id != ""){
								$query_where .= ' AND order_id = "'.$s_id.'"';
						} 
						$query_where .= ' AND order_club_id = "'.$club->get_id().'" AND order_type != "customer" ';
						
						if($s_status != ""){
								$query_where .= ' AND order_status = "'.$s_status.'"';
						}		

						$query = "SELECT *, (SELECT SUM(payment_amount) FROM payment WHERE payment_order_id = orders.order_id OR payment_order_child_id = orders.order_id) AS paymenttotal
						FROM orders
						LEFT JOIN club ON orders.order_club_id = club.club_id
						LEFT JOIN orderstatus ON orders.order_status = orderstatus.orderstatus_id
						WHERE 1=1 AND orders.is_active = 'Y' " . $query_where .$order;
						
						//Handle the sorting of the records
						$session->setQuery($_SERVER["PHP_SELF"],$query);
						$session->setSort($_SERVER["PHP_SELF"],$s_sort);
						$session->setSortDir($_SERVER["PHP_SELF"],$s_sort_dir);
					}else{
						//The page is not reloaded so use the query from the session
						$query = $session->getQuery($_SERVER["PHP_SELF"]);
					}

					if(isset($_GET['page'])){$page = $_GET['page'];}else{$page = 1;}
					$session->setPage($page);
					
					require_once(CLASSES ."/class_record_pager.php");
					$pager=new Pager($query,'paginglinks',20,0,1,'page_templates/orders_list_club_template.htm');
					echo $pager->displayRecords(mysql_escape_string($page));
					//echo $query;
			 ?>
        </div>

    </div> 
  </div><!-- /container -->
</div>


<?php include(INCLUDES. "footer.php");  ?>
<?php include(INCLUDES_LIST); ?>
    
<?php  if ($session->getSort($_SERVER["PHP_SELF"]) != ""){
  // If there is a sort saved in session, print a jquery function to add class to the selected column
   ?>
<script>

$(function() {
	  $('#<?php  echo $session->getSort($_SERVER["PHP_SELF"]);  ?>').addClass('sort_<?php  echo $session->getSortDir($_SERVER["PHP_SELF"])  ?>');
	  /* Set the input field for sort direction so that it can be toggled*/
	  $('#sort_dir').val("<?php  echo $session->getSortDir($_SERVER['PHP_SELF'])  ?>")
  }); 
</script>

<?php  }  ?>
  <script> 
$('.sort_column').click(function(){
  // Field clicked; 
  // - Set sort & sort direction input values
  // - Submit form
  $('#sort').val($(this).attr('id'));
  
 if ($('#sort_dir').val() == "asc"){
 		$('#sort_dir').val("desc");
	} else {
		$('#sort_dir').val("asc");
	}
  $('#frmFilter').submit();
});
  
$(document).ready(function() { 
	$('#search_toggle').click(function() {
		$('#search').toggle();
	});	
});

$(".clear").bind("click", function() {
  $("input[type=text], input[type=number], textarea, select").val("");
  $('#frmFilter').submit();  
});

  </script>
  </body>
</html>
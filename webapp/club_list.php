<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "/class_user.php"); 
include(CLASSES . "/class_club.php");
$activeMenuItem = "Clubs";

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('log_errors_max_len',0);
ini_set('error_log','/error_log.txt');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php  include(HEAD);  ?>
    <title><?php  echo $appConfig["app_title"];  ?> | Club List</title>
  </head>
  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<?php 
	$dm = new DataManager();
	
	$s_id = escaped_var_from_post("s_id");
	$s_name = escaped_var_from_post("s_name");
	$s_sport = escaped_var_from_post("s_sport");
	$s_brand = escaped_var_from_post("s_brand");
	$s_tel = escaped_var_from_post("s_tel");
	$s_city = escaped_var_from_post("s_city");
	$s_province = escaped_var_from_post("s_province");
	$s_code = escaped_var_from_post("s_code");
	$s_account_type = escaped_var_from_post("s_account_type");
	$s_active = escaped_var_from_post("s_active");
	$s_sort = escaped_var_from_post('sort');
	$s_sort_dir = escaped_var_from_post('sort_dir');
	
	if ($s_sort == ""){
		// if no sort is set, pick a default
		$s_sort = "club_id";
		$s_sort_dir = "desc";	
	}
	
	$order = " ORDER BY " . $s_sort . " " . $s_sort_dir;				
?>

<div class="main">
  <div class="container">
  
    <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>
        <h1><?php if ($s_account_type ==3){echo "Lead List";} else{ ?>Club List<?php } ?></h1>
		</div>
	</div>
		
      <div class="row">
        <div class="col-md-12">
		 <p><span id="search_toggle" title="Search/Filter Results"><i class="fa fa-search"></i> Search/Filter <i class="fa fa-chevron-down"></i></span> | 
        <i class="fa fa-plus-circle"></i> <a href="club_edit.php?id=0<?php if ($s_account_type ==3){echo "&account_type=3";} ?>">Add New Club</a></p>

        <div id="search">
          <form action="<?php  echo $_SERVER["PHP_SELF"]  ?>?reload=true" method="post" name="frmFilter" id="frmFilter">
            <table class="admin_table" style="display:block">
              <tr>
			  <td>Club Name</td><td>Sport</td><td>Brand</td><td>City</td><td>Province/State</td><td>Club Code</td><td>Account type</td><td>Active</td>
			  <td><input type="button" class="clear" value="Clear" /></td>
              </tr>
			  
              <tr>
				<td><input type="text" name="s_name"  value="<?php  echo $s_name ?>"/></td>
				<td><?php
					$dd = New DropDown();
					$dd->set_table("sport");
					$dd->set_name_field("sport_name");
					$dd->set_name("s_sport");
					$dd->set_selected_value($s_sport);
					$dd->set_order("ASC");	
					$dd->display();
				?></td>
				<td><?php
					$dd->clear();
					$dd->set_table("brand");
					$dd->set_name_field("brand_name");
					$dd->set_name("s_brand");
					$dd->set_selected_value($s_brand);
					$dd->set_order("ASC");	
					$dd->display();
				?></td>
				<td><input type="text" name="s_city"  value="<?php  echo $s_city  ?>" style="width:100px"/></td>
				<td><?php
					$dd->clear();
					$dd->set_table("province");
					$dd->set_name_field("province_name");
					$dd->set_name("s_province");
					$dd->set_selected_value($s_brand);
					$dd->set_order("ASC");	
					$dd->display();
				?></td>
				<td><input type="text" name="s_code"  value="<?php  echo $s_code  ?>" style="width:80px"/></td>
				<td><?php
					$dd->clear();
					$dd->set_table("accounttype");
					$dd->set_name_field("accounttype_title");
					$dd->set_name("s_account_type");
					$dd->set_selected_value($s_account_type);
					$dd->set_order("ASC");	
					$dd->display();
				?></td>				
				<td><?php
					$dd->clear();
					$dd->set_preset("is_active");	
					$dd->set_selected_value($s_active);	
					$dd->set_name("s_active");
					$dd->display();
				?></td>				
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
								$query_where .= ' AND club_id = "'.$s_id.'"';
						} 
						if($s_name != ""){
								$query_where .= ' AND club_name = "'.$s_name.'"';
						} 
						if($s_sport != ""){
								$query_where .= ' AND club_sport = "'.$s_sport.'"';
						} 
						if($s_brand != ""){
								$query_where .= ' AND club_brand = "'.$s_brand.'"';
						} 
						if($s_tel != ""){
								$query_where .= ' AND club_tel = "'.$s_tel.'"';
						} 
						if($s_city != ""){
								$query_where .= ' AND club_city = "'.$s_city.'"';
						} 
						if($s_province != ""){
								$query_where .= ' AND club_province = "'.$s_province.'"';
						} 
						if($s_code != ""){
								$query_where .= ' AND club_code = "'.$s_code.'"';
						} 
						if($s_account_type != ""){
								$query_where .= ' AND club_account_type = "'.$s_account_type.'"';
						} else {
								$query_where .= ' AND club_account_type != 3 ';
						}
						if($s_active != ""){
								$query_where .= ' AND club_active = "'.$s_active.'"';
						}		

						$query = "SELECT * FROM club 
						LEFT JOIN province ON club.club_province = province.province_id
						LEFT JOIN country ON club.club_country = country.country_id
						LEFT JOIN brand ON club.club_brand = brand.brand_id
						LEFT JOIN sport ON club.club_sport = sport.sport_id
						LEFT JOIN accounttype ON club.club_account_type = accounttype.accounttype_id
						WHERE 1=1" . $query_where .$order;
						
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
					$pager=new Pager($query,'paginglinks',20,0,1,'page_templates/club_list_template.htm');
					echo $pager->displayRecords(mysql_escape_string($page));
					//echo $query;
			 ?>
        </div>

    </div> 
  </div><!-- /container -->
</div>


<?php  include(INCLUDES. "footer.php");  ?>
<?php require(INCLUDES_LIST);?>	

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
	<?php  if ($query_where != ""){
	echo "$('#search').toggle();";
	} ?>	
});

$(".clear").bind("click", function() {
  $("input[type=text], input[type=number], textarea, select").val("");
  $('#frmFilter').submit();  
});

  </script>
  </body>
</html>
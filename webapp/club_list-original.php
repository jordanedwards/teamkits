<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "/class_user.php"); 
include(CLASSES . "/class_club.php");
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Orchard City Web Development">
    <link rel="icon" href="favicon.ico">
	
    <title><?php  echo $appConfig["app_title"];  ?> | Club List</title>

    <!-- Bootstrap core CSS -->   
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/bootstrap-responsive.min.css" rel="stylesheet">
    
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="./css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">        
    
    <link href="./css/ui-lightness/jquery-ui-1.10.0.custom.min.css" rel="stylesheet">
    
    <link href="./css/base-admin-3.css" rel="stylesheet">
    <link href="./css/base-admin-3-responsive.css" rel="stylesheet">
    
    <link href="./css/pages/dashboard.css" rel="stylesheet">   
    <link href="./css/custom.css" rel="stylesheet">
    <link href="./css/styles.css" rel="stylesheet">
	
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>

<div class="main">
  <div class="container">
  
    <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>
        <h1>Club List</h1>
		</div>
	</div>
		
      <div class="row">
        <div class="col-md-12">
		 <p><span id="search_toggle" title="Search/Filter Results"><i class="fa fa-search"></i> Search/Filter <i class="fa fa-chevron-down"></i></span> | 
        <i class="fa fa-plus-circle"></i> <a href="club_edit.php?id=0">Add New Club</a></p>
<?php 

$dm = new DataManager();

	$s_id = escaped_var_from_post("s_id");
	$s_name = mysqli_real_escape_string($dm->connection, $_REQUEST["s_name"]);
	$s_sport = mysqli_real_escape_string($dm->connection, $_REQUEST["s_sport"]);
	$s_brand = mysqli_real_escape_string($dm->connection, $_REQUEST["s_brand"]);
	$s_tel = mysqli_real_escape_string($dm->connection, $_REQUEST["s_tel"]);
	$s_city = mysqli_real_escape_string($dm->connection, $_REQUEST["s_city"]);
	$s_province = mysqli_real_escape_string($dm->connection, $_REQUEST["s_province"]);
	$s_code = mysqli_real_escape_string($dm->connection, $_REQUEST["s_code"]);
	$s_account_type = mysqli_real_escape_string($dm->connection, $_REQUEST["s_account_type"]);
	$s_active = mysqli_real_escape_string($dm->connection, $_REQUEST["s_active"]);
	$s_sort = mysqli_real_escape_string($dm->connection, $_POST['sort']);
	$s_sort_dir = mysqli_real_escape_string($dm->connection, $_POST['sort_dir']);
	
	if ($s_sort == ""){
		// if no sort is set, pick a default
		$s_sort = "club_id";
		$s_sort_dir = "desc";	
	}
	
	$order = " ORDER BY " . $s_sort . " " . $s_sort_dir;		
					
 ?>
        <div id="search">
          <form action="<?php  echo $_SERVER["PHP_SELF"]  ?>?reload=true" method="post" name="frmFilter" id="frmFilter">
            <table class="admin_table" style="display:block">
              <tr>
			  <td>Club Name</td><td>Sport</td><td>Brand</td><td>City</td><td>Province/State</td><td>Club Code</td><td>Account type</td><td>Active</td>
			  <td><input type="button" class="clear" value="Clear" /></td>
              </tr>
			  
              <tr>
				<td><input type="text" name="s_name"  value="<?php  echo $_POST["s_name"]  ?>"/></td>
				<td>
				<?php
					$dd = New DropDown();
					$dd->set_table("sport");
					$dd->set_name_field("sport_name");
					$dd->set_name("s_sport");
					$dd->set_selected_value($student_value);
					$dd->set_class_name("form-control");
					$dd->set_active_only(true);
					$dd->set_required(false);	
					$dd->set_onchange("updateStudent();");
					$ddt->set_order("ASC");	
					$dd->display();
				?>
				<input type="text" name="s_sport"  value="<?php  echo $_POST["s_sport"]  ?>"/></td>
				<td><input type="text" name="s_brand"  value="<?php  echo $_POST["s_brand"]  ?>"/></td>
				<td><input type="text" name="s_tel"  value="<?php  echo $_POST["s_tel"]  ?>"/></td>
				<td><input type="text" name="s_city"  value="<?php  echo $_POST["s_city"]  ?>"/></td>
				<td><input type="text" name="s_province"  value="<?php  echo $_POST["s_province"]  ?>"/></td>
				<td><input type="text" name="s_code"  value="<?php  echo $_POST["s_code"]  ?>"/></td>
				<td><input type="text" name="s_account_type"  value="<?php  echo $_POST["s_account_type"]  ?>"/></td>
				<td><input type="text" name="s_active"  value="<?php  echo $_POST["s_active"]  ?>"/></td>
								<input type="hidden" id="sort" name="sort" value="<?php echo $_POST['sort']?>" />
				<input type="hidden" id="sort_dir" name="sort_dir" value="<?php echo $_POST['sort_dir']?>" />
								
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
						} 
						if($s_active != ""){
								$query_where .= ' AND club_active = "'.$s_active.'"';
						}		

						$query = "SELECT * FROM club 
						LEFT JOIN province ON club.club_province = province.province_id
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

	
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script src="../js/Application.js"></script>

<script type="text/javascript" src="../js/jquery.metadata.js"></script>
<script type="text/javascript" src="../js/jquery.validate.js"></script>
    
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
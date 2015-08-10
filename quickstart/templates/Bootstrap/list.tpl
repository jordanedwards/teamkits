<dynamic>
require("<?php echo $this->settings['includes_url']?>/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "/class_user.php"); 
include(CLASSES . "/class_<?php echo $this->selected_table ?>.php");
$activeMenuItem = "<?php echo ucfirst($this->selected_table) ?>";
</dynamic>
	
<!DOCTYPE html>
<html lang="en">
  <head>
	<dynamic> include(HEAD); </dynamic>
    <meta name="description" content="">
    <title><dynamic> echo $appConfig["app_title"]; </dynamic> | <?php echo ucfirst($this->selected_table) ?> List</title>
  </head>

  <body>

<dynamic> require(INCLUDES . "navbar.php"); </dynamic>

<div class="main">
  <div class="container">
  
    <div class="row">
        <div class="col-md-12">
        <dynamic> include(INCLUDES . "system_messaging.php"); </dynamic>
        <h1><?php echo ucfirst($this->selected_table) ?> List</h1>
		</div>
	</div>
		
      <div class="row">
        <div class="col-md-12">
		 <p><span id="search_toggle" title="Search/Filter Results"><i class="fa fa-search"></i> Search/Filter <i class="fa fa-chevron-down"></i></span> | 
        <i class="fa fa-plus-circle"></i> <a href="<?php echo $this->selected_table ?>_edit.php?id=0">Add New <?php echo ucfirst($this->selected_table) ?></a></p>
<dynamic>

$dm = new DataManager();

	<?php
foreach($this->field_names as $key => $val){
echo '$s_' . $val . ' = escaped_var_from_post("s_' . $val. '");
	';
}	
?>
$s_sort = escaped_var_from_post('sort');
	$s_sort_dir = escaped_var_from_post('sort_dir');

	if ($s_sort == ""){
		// if no sort is set, pick a default
		$s_sort = "<?php echo $this->selected_table . "." . $this->index_name ?>";
		$s_sort_dir = "desc";	
	}

	$order = " ORDER BY " . $s_sort . " " . $s_sort_dir;		
					
</dynamic>
        <div id="search">
          <form action="<dynamic> echo $_SERVER["PHP_SELF"] </dynamic>?reload=true" method="post" name="frmFilter" id="frmFilter">
            <table class="admin_table" style="display:block">
              <tr>
			  <?php
foreach($this->field_names as $key => $val){
				echo "<td>" . str_replace("_"," ",ucfirst($val)) . "</td>";
}
?>
				<td><input type="button" class="clear" value="Clear" /></td>
              </tr>
			  
              <tr>
			  <?php
foreach($this->field_names as $key => $val){
				echo '<td><input type="text" name="s_' . $val. '"  value="<dynamic> echo $s_' . $val. ' </dynamic>"/></td>
				';
}
?>
				<input type="hidden" id="sort" name="sort" value="<?php print("<?php"); ?> echo $sort?>" />
				<input type="hidden" id="sort_dir" name="sort_dir" value="<?php print("<?php"); ?> echo $sort_dir?>" />
								
                <td valign="top"><input type="submit" class="submit" value="Search" /></td>
              </tr>
            </table>
          </form>
        </div>
			<br>
<dynamic>
		$query = $session->getQuery($_SERVER["PHP_SELF"]);
		$reload = (isset($_GET['reload']) && $_GET['reload'] == "true" && isset($_GET['page']) == false ? $_GET['reload'] : "");
		$query_where = "";
		
		if ($query == "" || $reload == "true") {
		// Page set to reload (new query)		
				<?php
foreach($this->field_names as $key => $val){
	echo ' 
			if($s_' . $val . ' != ""){
				$query_where .= \' AND ' . $val . ' = "\'.$s_' . $val . ".'\"';
			}";
}
?>		

			$query = "SELECT *, <?php echo $this->selected_table ?>.id AS <?php echo $this->selected_table ?>Id
			FROM <?php echo $this->selected_table ?>			
			WHERE 1=1 AND <?php echo $this->selected_table ?>.is_active='Y' " . $query_where .$order;
			
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
		$pager=new Pager($query,'paginglinks',20,0,1,'page_templates/<?php echo $this->selected_table ?>_list_template.htm');
		echo $pager->displayRecords(mysqli_escape_string($dm->connection,$page));
		
		if ($appConfig["environment"] == "development"){
			consoleLog($query);
		}
			</dynamic>
        </div>

    </div> 
  </div><!-- /container -->
</div>


<dynamic> include(INCLUDES. "footer.php"); </dynamic>
<dynamic> include(INCLUDES_LIST); </dynamic>
    
<dynamic> if ($session->getSort($_SERVER["PHP_SELF"]) != ""){
  // If there is a sort saved in session, print a jquery function to add class to the selected column
  </dynamic>
<script>

$(function() {
	  $('#<dynamic> echo $session->getSort($_SERVER["PHP_SELF"]); </dynamic>').addClass('sort_<dynamic> echo $session->getSortDir($_SERVER["PHP_SELF"]) </dynamic>');
	  /* Set the input field for sort direction so that it can be toggled*/
	  $('#sort_dir').val("<dynamic> echo $session->getSortDir($_SERVER['PHP_SELF']) </dynamic>")
  }); 
</script>

<dynamic> } </dynamic>
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
	<dynamic> if ($query_where != ""){
	echo "$('#search').toggle();";
	}</dynamic>	
});

$(".clear").bind("click", function() {
  $("input[type=text], input[type=number], textarea, select").val("");
  $('#frmFilter').submit();  
});

  </script>
  </body>
</html>
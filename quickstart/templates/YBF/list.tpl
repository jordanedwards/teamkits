<dynamic>
require_once($_SERVER['DOCUMENT_ROOT'] . "/<?php echo ($this->settings['base_url'] != '' ? $this->settings['base_url'] . "/" : "")?><?php echo $this->settings['includes_url']?>/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
require_once(CLASSES . "/class_drop_downs.php");
$dd = New DropDown();
include(CLASSES . "/class_<?php echo $this->selected_table ?>.php");

$activeMenuItem = "<?php echo ucfirst($this->selected_table) ?>"; 
</dynamic>
<!DOCTYPE html>
<html lang="en">
	<head>
		<dynamic> include(HEAD); </dynamic>
    	<title><?php echo ucfirst($this->selected_table) ?> List | <dynamic> echo $appConfig["app_title"]; </dynamic></title>
	</head>	
<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
			<dynamic> require(INCLUDES . "navbar.php"); </dynamic>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">

			<div id="sidebar" class="sidebar responsive">
			<dynamic> require(INCLUDES . "sidebar_menu.php"); </dynamic>
			</div>
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>

							<li>
								<a href="#"><?php echo ucfirst($activeMenuItem) ?></a>
							</ul><!-- /.breadcrumb -->

					<dynamic> include(INCLUDES . "search.php"); </dynamic>

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								<?php echo ucfirst($this->selected_table) ?>
							</h1>
						</div><!-- /.page-header -->

  
<div class="" style="margin-left: initial;">

        <div class="col-md-12">
        <dynamic> include(INCLUDES . "system_messaging.php"); </dynamic>
		</div>

		
      <div class="row">
        <div class="col-md-12">
		 <p><span id="search_toggle" title="Search/Filter Results"><i class="fa fa-search"></i> Search/Filter</span> <i class="fa fa-chevron-down"></i> | 
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
				$s_sort = "<?php echo $this->index_name ?>";
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
				echo "<td>" . ucfirst($this->selected_table) . " " . ucfirst($val) . "</td>";
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
					
					if ($query == "" || $reload == "true") {
					// Page set to reload (new query)		
							<?php
foreach($this->field_names as $key => $val){
				echo ' 
						if($s_' . $val . ' != ""){
								$query_where .= \' AND ' . $this->selected_table . '_' . $val . ' = "\'.$s_' . $val . ".'\"';
						}";
}
?>		

						$query = "SELECT * from <?php echo $this->selected_table ?> WHERE 1=1" . $query_where .$order;
						
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
					//echo $query;
			</dynamic>
        </div>

    </div> 
</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

	<dynamic> include(INCLUDES . "footer.php"); </dynamic>

			</a>
		</div>

<dynamic> include(INCLUDES_LIST); </dynamic>

		<!--[if !IE]> -->
		<script src="js/jquery-1.11.2.min.js"></script>
		<!-- <![endif]-->
			
	<script type="text/javascript">
		if('ontouchstart' in document.documentElement) document.write("<script src='../assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
	</script>
	<script src="template/assets/js/bootstrap.js"></script>

	<!-- page specific plugin scripts -->
	<script src="template/assets/js/dataTables/jquery.dataTables.js"></script>
	<script src="template/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
	<script src="template/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
	<script src="template/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

	<!-- ace scripts -->
	<script src="template/assets/js/ace/elements.scroller.js"></script>
	<script src="template/assets/js/ace/elements.colorpicker.js"></script>
	<script src="template/assets/js/ace/elements.treeview.js"></script>
	<script src="template/assets/js/ace/elements.wizard.js"></script>
	<script src="template/assets/js/ace/elements.aside.js"></script>
	<script src="template/assets/js/ace/ace.js"></script>
	<script src="template/assets/js/ace/ace.ajax-content.js"></script>
	<script src="template/assets/js/ace/ace.touch-drag.js"></script>
	<script src="template/assets/js/ace/ace.sidebar.js"></script>
	<script src="template/assets/js/ace/ace.sidebar-scroll-1.js"></script>
	<script src="template/assets/js/ace/ace.submenu-hover.js"></script>
	<script src="template/assets/js/ace/ace.widget-box.js"></script>
	<script src="template/assets/js/ace/ace.settings.js"></script>
	<script src="template/assets/js/ace/ace.settings-rtl.js"></script>
	<script src="template/assets/js/ace/ace.settings-skin.js"></script>
	<script src="template/assets/js/ace/ace.widget-on-reload.js"></script>
	<script src="template/assets/js/ace/ace.searchbox-autocomplete.js"></script>

	<link rel="stylesheet" href="template/docs/assets/js/themes/sunburst.css" />
    
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
	<dynamic>  if ($query_where != ""){
	echo "$('#search').toggle();";
	} </dynamic>
	
});

$(".clear").bind("click", function() {
  $("input[type=text], input[type=number], textarea, select").val("");
  $('#frmFilter').submit();  
});

  </script>
  </body>
</html>
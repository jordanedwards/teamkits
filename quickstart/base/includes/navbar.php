<nav class="navbar navbar-inverse" role="navigation">

	<div class="container" style="background: rgba(255,255,255,0.6);">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header" style="  padding-left: 40px;">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <i class="fa fa-cog"></i>
    </button>
    <a class="navbar-brand" href="./index.php"><img src="<?php echo BASE_URL ?>/images/<?php echo LOGO ?>" style="height:80px"></a>
  </div>

<?php if($session->get_user_id() != ""): ?>
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav navbar-right">
    <li class="dropdown"><a href="./settings.php" style="text-shadow:none;"><i class="fa fa-cog"></i>&nbsp;Settings</a></li>
						

		<li class="dropdown">
						
			<a href="javscript:;" class="dropdown-toggle" data-toggle="dropdown"  style="text-shadow:none;">
				<i class="fa fa-user"></i>&nbsp;
				<?php 
				if ($currentUser){
					echo $currentUser->get_user_name();
				}
				?>
				<b class="caret"></b>
			</a>
			
			<ul class="dropdown-menu">
				<li><a href="./actions/action_logout.php">Logout</a></li>
			</ul>
			
		</li>
    </ul>

    <form class="navbar-form navbar-right" role="search">
      <div class="form-group">
        <input type="text" class="form-control input-sm search-query" placeholder="Search">
      </div>
    </form>
  </div>
  <?php endif; ?>
</div> <!-- /.container -->
</nav>

<?php if($session->get_user_id() != ""): ?>
<div class="subnavbar">

	<div class="subnavbar-inner">
	
		<div class="container">
			
			<a href="javascript:;" class="subnav-toggle" data-toggle="collapse" data-target=".subnav-collapse">
		      <span class="sr-only">Toggle navigation</span>
		      <i class="fa fa-reorder"></i>
		      
		    </a>

			<div class="collapse subnav-collapse">
				<ul class="mainnav">
				<?php if ($activeMenuItem == NULL){ $activeMenuItem == "Home";} ?>
					<li <?php if ($activeMenuItem == "Home") { echo 'class="active"'; } ?>>
						<a href="./dashboard.php">
							<i class="fa fa-home"></i>
							<span>Home</span>
						</a>	    				
					</li>

					<li <?php if ($activeMenuItem == "Clubs") { echo 'class="active"'; } ?>>
						<a href="./customer_list.php">
							<i class="fa fa-users"></i>
							<span>Customers</span>
						</a>	    				
					</li>			

					<li <?php if ($activeMenuItem == "Orders") { echo 'class="active"'; } ?>>
						<a href="./orders_list.php">
							<i class="fa fa-archive"></i>
							<span>Orders</span>
						</a>	    				
					</li>							
																									
					<li class="dropdown <?php if ($activeMenuItem == "Manage") { echo ' active '; } ?>" >					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-th"></i>
							<span>Manage</span>
							<b class="caret"></b>
						</a>	    
					
						<ul class="dropdown-menu">
							<li><a href="./brand_list.php?reload=true">Brands</a></li>						
							<li><a href="./item_list.php?reload=true">Items</a></li>						
							<li><a href="./promo_list.php"><span>Promos</span></a></li>										
							<li><a href="./user_list.php?reload=true">Users</a></li>
						</ul> 				
					</li> 

					
				</ul>
			</div> <!-- /.subnav-collapse -->

		</div> <!-- /container -->
	
	</div> <!-- /subnavbar-inner -->

</div>
  <?php endif; ?>
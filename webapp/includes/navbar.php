<nav class="navbar navbar-inverse" role="navigation">

	<div class="container" style="background: rgba(255,255,255,0.6);">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header" style="  padding-left: 40px;">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <i class="fa fa-cog"></i>
    </button>
    <a class="navbar-brand" href="/index.php"><img src="/images/<?php echo LOGO ?>" style="height:80px"></a>
  </div>

<?php if($session->get_user_id() != ""): ?>
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav navbar-right">
   <!-- <li class="dropdown"><a href="/webapp/settings.php" style="text-shadow:none;"><i class="fa fa-cog"></i>&nbsp;Settings</a></li>-->					

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
			<?php if ($session->getMask() > 0){ ?>
				<li><a href="/webapp/actions/action_mask.php?return=true">Return</a></li>
			<?php } ?>
				<li><a href="/webapp/actions/action_logout.php">Logout</a></li>
			</ul>
			
		</li>
    </ul>
    <!--
    <form class="navbar-form navbar-right" role="search">
      <div class="form-group">
        <input type="text" class="form-control input-sm search-query" placeholder="Search">
      </div>
    </form>-->
  </div>
  <?php endif; ?>
</div> <!-- /.container -->
</nav>

<?php if($session->get_user_id() != "" && $session->get_user_role() == 1 || $session->get_user_id() != "" && $session->get_user_role() == 2): 
// Admins and superusers
?>
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
						<a href="/webapp/dashboard.php">
							<i class="fa fa-home"></i>
							<span>Home</span>
						</a>	    				
					</li>
					
					<li <?php if ($activeMenuItem == "Clubs") { echo 'class="active"'; } ?>>
						<a href="/webapp/club_list.php">
							<i class="fa fa-users"></i>
							<span>Clubs</span>
						</a>	    				
					</li>	
					
					<li <?php if ($activeMenuItem == "Leads") { echo 'class="active"'; } ?>>
						<a href="/webapp/lead_list.php">
							<i class="fa fa-bolt"></i>
							<span>Leads</span>
						</a>	    				
					</li>			

					<li <?php if ($activeMenuItem == "Orders") { echo 'class="active"'; } ?>>
						<a href="/webapp/orders_list.php">
							<i class="fa fa-archive"></i>
							<span>Orders</span>
						</a>	    				
					</li>	
							
					<li <?php if ($activeMenuItem == "Website") { echo 'class="active"'; } ?>>
						<a href="/webapp/cmscomponent_list.php">
							<i class="fa fa-laptop"></i>
							<span>Website</span>
						</a>	    				
					</li>	
					

					<li <?php if ($activeMenuItem == "Payments") { echo 'class="active"'; } ?>>
						<a href="/webapp/payment_list.php">
							<i class="fa fa-money"></i>
							<span>Payments</span>
						</a>	    				
					</li>							
																									
					<li class="dropdown <?php if ($activeMenuItem == "Manage") { echo ' active '; } ?>" >					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-th"></i>
							<span>Manage</span>
							<b class="caret"></b>
						</a>	    
					
						<ul class="dropdown-menu">
							<li><a href="/webapp/brand_list.php?reload=true">Brands</a></li>		
							<li><a href="/webapp/currency_list.php?reload=true">Currencies</a></li>																	
							<li><a href="/webapp/item_list.php?reload=true">Items</a></li>						
							<li><a href="/webapp/promo_list.php"><span>Promos</span></a></li>										
							<li><a href="/webapp/user_list.php?reload=true">Users</a></li>
						</ul> 				
					</li> 

					<li >					
						<a href="https://orchardcity.ca/support/">
							<i class="fa fa-ticket"></i>
							<span>Support Tickets</span>
						</a>		
					</li>
				</ul>
			</div> <!-- /.subnav-collapse -->

		</div> <!-- /container -->
	
	</div> <!-- /subnavbar-inner -->

</div>
<?php endif; ?>

<?php if($session->get_user_id() != "" && $session->get_user_role() == 3): 
// Clubs
?>
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
						<a href="./dashboard_club.php">
							<i class="fa fa-home"></i>
							<span>Home</span>
						</a>	    				
					</li>

					<li <?php if ($activeMenuItem == "Account info") { echo 'class="active"'; } ?>>
						<a href="/webapp/club_admin/club_view.php">
							<i class="fa fa-user"></i>
							<span>Account info</span>
						</a>	    				
					</li>			

					<li <?php if ($activeMenuItem == "Orders") { echo 'class="active"'; } ?>>
						<a href="./orders_list_club.php">
							<i class="fa fa-archive"></i>
							<span>Orders</span>
						</a>	    				
					</li>	
										
					<li <?php if ($activeMenuItem == "Order_edit") { echo 'class="active"'; } ?>>
						<a href="./orders_edit.php?id=0">
							<i class="fa fa-plus-circle"></i>
							<span>New Order</span>
						</a>	    				
					</li>	
					
					<li <?php if ($activeMenuItem == "Catalogue") { echo 'class="active"'; } ?>>
						<a href="./catalogue.php">
							<i class="fa fa-book"></i>
							<span>Catalogue</span>
						</a>	    				
					</li>							

																														
				<!--	<li class="dropdown <?php if ($activeMenuItem == "Manage") { echo ' active '; } ?>" >					
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
					</li> -->

					
				</ul>
			</div> <!-- /.subnav-collapse -->

		</div> <!-- /container -->
	
	</div> <!-- /subnavbar-inner -->

</div>
<?php endif; ?>

<?php if( $session->get_user_role() == 4): 
// Members
?>
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
						<a href="./dashboard_member.php">
							<i class="fa fa-home"></i>
							<span>Home</span>
						</a>	    				
					</li>		

					<li <?php if ($activeMenuItem == "Cart") { echo 'class="active"'; } ?>>
						<a href="./cart.php">
							<i class="fa fa-shopping-cart"></i>
							<span>Shopping Cart</span>
						</a>	    				
					</li>
										
					<li <?php if ($activeMenuItem == "Catalogue") { echo 'class="active"'; } ?>>
						<a href="./catalogue.php">
							<i class="fa fa-book"></i>
							<span>Catalogue</span>
						</a>	    				
					</li>
					
					<li><a href="/webapp/actions/action_logout.php"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>								

					
				</ul>
			</div> <!-- /.subnav-collapse -->

		</div> <!-- /container -->
	
	</div> <!-- /subnavbar-inner -->

</div>
<?php endif; ?>
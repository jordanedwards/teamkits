<?php 
$public=true;
require("webapp/includes/init.php"); 
include(CLASSES . "/class_cmscomponent.php");
?>
<!DOCTYPE html>
<html class=" js flexbox canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths"><!--<![endif]-->
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="Cache-control" content="public" max-age=3600>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Teamkits</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	
	<!-- favicon.ico and apple-touch-icon.png -->
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="apple-touch-icon" href="gfi/apple-touch-icon-57x57-precomposed.png">
	<link rel="apple-touch-icon" sizes="72x72" href="gfi/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" sizes="114x114" href="gfi/apple-touch-icon-114x114-precomposed.png">
	<link href='https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Rock+Salt' rel='stylesheet' type='text/css'>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	
	<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-46457738-1']);
			_gaq.push(['_trackPageview']);
			
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script> 
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
	<!--<link rel="stylesheet" href="css/timeline.css">
	<link rel="stylesheet" href="css/main.css">-->
<!--	<link rel="stylesheet" href="css/responsive.css">-->
	
	<link rel="stylesheet" href="css/combined.css">	 <!-- replaces the above stylesheets -->
		
	<link rel="stylesheet" href="css/styles.css">	
<style>
.og-grid li>a, .og-grid li>a img {
    max-width: 150px;
}
.og-details ul.thumbs {
	max-width:none;
	padding-left: 0;
    padding-bottom: 20px;
}
.og-details a {
    margin: 5px 0 0;
}
.item {
  margin-bottom: 5px;
}
</style>
    </head>
    <body data-spy="scroll" data-target=".navbar">
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '183604241973561',
      xfbml      : true,
      version    : 'v2.4'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
        <div id="fb-root"></div>
        <section id="home">
            <div class="container">
                <div class="containerblack">
                    <ul class="homenav left">
                        <li><a href="#about" class="scrollto">About</a></li>
                        <li><a href="#works" class="scrollto">Brands</a></li>
                    </ul>
                    <ul class="homenav right">
                        <li><a href="#timeline" class="scrollto">Order Info</a></li>
                        <li><a href="#contact" class="scrollto">Contact</a></li>
                        <li class="login"><a href="login.php">Login</a></li>							
						
                    </ul>
                    <h1><img src="/images/teamkits_logo_white.png" id="logo" ></h1>
					</div>  
                </div>
                <a href="#works" id="arrow_down">What we do<br><span class="glyphicon glyphicon-chevron-down"></span></a>
            </div>                  	
        </section>

        <div class="sticky-wrapper" style="position:relative">
			<div class="taper taper-top"><img src="img/taper_top.png" alt=""></div>
			<nav class="navbar" style="height: 56px;">
			
	
				<div class="navbar-inner">
					<div class="container">
						<a class="navbar-brand" href="#"><img src="/images/teamkits.png"></a>
						<div class="pull-right">
							<ul class="nav">
								<li class="active" id="homelink"><a href="#" class="scrollto menu-item">Home</a></li>
								<li class=""><a href="#works" class="scrollto menu-item">Brands</a></li>
								<li class=""><a href="#about" class="scrollto menu-item">About</a></li>
								<li class=""><a href="#timeline" class="scrollto menu-item">Order INFO</a></li>							
								<li class=""><a href="#contact" class="scrollto menu-item">Contact</a></li>
								<li class=""><a href="login.php" class="login"><i class="fa fa-sign-in"></i></a></li>							
							</ul>
						</div>
					</div>
				</div>
			</nav>
			<div class="taper taper-bottom"><img src="img/taper_bottom.png" alt=""></div>
		</div>
       
        <section id="works">
            <div class="container">
                <h2 class="text-center">BRANDS<i class="fa fa-tags fa-2x icon-works"></i></h2>
                <p class="text-center lead">We are proud to work with the world's leading names in sportswear. Click to view details about these fine companies:</p>
            </div> <!-- end: container -->
            <div id="works_items">
                <ul id="og-grid" class="og-grid">
				<?php 
				
				$dm = new DataManager(); 
				$strSQL = "SELECT * 
				FROM brand 
				WHERE brand_feature='Y' AND is_active='Y'
				ORDER BY brand_name ASC
				";
				
				$result = $dm->queryRecords($strSQL);	
				if ($result):
					while($row = mysqli_fetch_assoc($result)):
					?>
					<li class="brand-icons">
						<a href="#" data-largesrc="/img/brands/<?php echo strtolower($row['brand_name']) ?>/<?php echo $row['brand_main_image'] ?>"  data-title="<?php echo ucfirst($row['brand_name']) ?>" data-description="<?php echo $row['brand_description'] ?>">
							<img src="img/brands/<?php echo $row['brand_logo'] ?>" alt="<?php echo ucfirst($row['brand_name']) ?>"><div><?php echo ucfirst($row['brand_name']) ?></div>
						</a>
						
						<?php 
						$brand = $row['brand_name'];
						
						$subSQL = "SELECT * 
						FROM brandcatalogue
						WHERE brand_id=" .$row['brand_id'] . " AND is_active='Y'";
						
						$subresult = $dm->queryRecords($subSQL);	
						if ($subresult):
							echo '<div class="thumbs">
							';
							while($subrow = mysqli_fetch_assoc($subresult)):
								echo '<a href="' . $subrow['url']. '" data-largesrc="/img/brands/' . strtolower($brand) .'/' . $subrow['image']. '" data-thumb="/img/brands/' . strtolower($brand) .'/' . $subrow['image']. '" data-title="' . $subrow['title']. '" data-description="' . $subrow['description']. '" ></a>
								';
							endwhile;
							echo '
							</div>';
						endif;
						?>

					</li>					

					<?
					endwhile;									
				endif;			
				?>
			
                </ul>
            </div><!-- end: #works_items -->
        </section>

<div style="position:relative">		
<div class="taper taper-top-full"><img src="img/full_stripe.png" alt=""></div>
		<!--<div class="band-white band-top col-md-12" style="border:none;"></div>-->
        <div class="container txtblock">
            <p class="text-center lead-style">Style is a way to say who you are without having to speak. <a href="#contact" class="scrollto">Let`s talk.</a></p>
        </div> 
		<div class="band-white band-bottom col-md-12"></div>
	</div>
		
        <section id="about">
            <div class="container">
                <h2 class="text-center">About<i class="icon-about"><img src="images/icon-about.png"></i></h2>

				<div class="col-md-8" style="padding:10px">

				
				<p class="text-center lead"><!--<span class="little-drop-cap">T</span>eam Kits, based in Kelowna, BC, and having direct-from-factory relationships with Precision Training from the UK, Macron, Joma, Inaria, and Lotto from Italy and dealerships with Xara, Umbro, Mitre, Molten, and others believes that a uniform is not something that you just play in, but much more to make a statement of who your club or team is.</p><br>
				
<p class="text-center lead">We are happy to offer our US customers in the Pacific Northwest our unique and exclusive “Made in America” theme that provides an exclusive branded design that is yours and will be always available when you need a kit.</p><br>

<p class="text-center lead">Team Kits provides a custom look for competitive and professional teams but also delivers high volume Value Kits for recreational clubs and teams.  With our designers, Team Kits provides a “Brand Concept” that makes a team or club unique, versus looking the same as the teams they play against.</p><br>

<p class="text-center lead">Soccer is a business, and for a pro team, revenue generation from replica sales is a must have and can make the difference on the profit and loss statement at the end of the year.  For our pro teams, the kits become a value-added revenue source. It doesn’t make sense to ask a fan to part with $70 to buy an off-the-shelf jersey when they can buy the same jersey anywhere online for less.  While a fan is usually happy just to support the team, wanting to show off the jersey because it’s unique is something special.  All this goes towards generating income to make the team stronger.</p><br><br>-->

<?php get_component("About us"); ?>
				<a class="btn button-cool scrollto" href="#contact">Contact Us</a>
				</p>
				</div>
	
				<div class="col-md-4">
					<div class="fb-page" data-href="https://www.facebook.com/teamkitscanada" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"> </div>
				</div>
   				</div>
				
            </div><!-- end: .container -->
        </section>
		
		<div class="band-white band-top col-md-12"></div>		
        <div class="container txtblock nrtwo">
				<p class="lead no-background dark cursive">Be Distinctive. Be Original. Be Bold.</p><p class="lead no-background"><a href="#contact" class="scrollto">READY?</a></p>
        </div> <!-- end: container txtblock -->
		<div class="band-white band-bottom  col-md-12"></div>

        <section id="timeline">
            <div class="container">
			<div class="col-md-12">
			      <h2 class="text-center">The ordering process<i class="icon-time"><img src="images/icon-time.png"></i></h2>
			</div>
			<div class="col-md-5" style="padding: 0 10px 0 0;">
               <!-- <p class="text-center lead">.</p> -->
                <ul class="cbp_tmtimeline">
                    <li>
                        <div class="cbp_tmicon"><span>1</span></div>
                        <div class="cbp_tmlabel">
                            <h2>Set up your account.</h2>
                            <p>Contact us to have your account set up. Once it is, you will be able to log in from this site and handle your orders.</p>
                        </div>
                    </li>
                    <li>
                        <div class="cbp_tmicon"><span>2</span></div>
						<div class="cbp_tmlabel">
                            <h2>Upload your logo and team colours.</h2>
                            <p>We will send your logo and team colours to the manufacturer and get a mockup made to suit your specifications.</p>
                        </div>
                    </li>
					<li>
                        <div class="cbp_tmicon"><span>3</span></div>
                        <div class="cbp_tmlabel">
                            <h2>Place your order</h2>
                            <p>We will upload an image of the custom order to your account. Give us the ok and we will send your jersey, shorts, socks, etc into production. We will generate your invoice and send it to you.</p>
                        </div>
                    </li>					
                    <li>
                        <div class="cbp_tmicon"><span>4</span></div>
                        <div class="cbp_tmlabel">
                            <h2>Proof your jersey</h2>
                            <p>We will upload an image of the custom order to your account. Give us the ok and we will send your jersey, shorts, socks, etc into production. We will generate your invoice and send it to you.</p>
                        </div>
                    </li>
                    <li>
                        <div class="cbp_tmicon"><span>5</span></div>
                        <div class="cbp_tmlabel">
                            <h2>Receive delivery</h2>
                            <p>From order to receipt can take between 3 and 6 weeks, depending on the location of the manufacturer. Our deliveries are all flagged with a tracking number so you can watch it's progress online.</p>
                        </div>
                    </li>
                </ul>
            </div>
			<div class="col-md-7" style="padding: 10px 0 0 10px;">
				<div id="masonry-content">
				<?php 
				
				$dm = new DataManager(); 
				$strSQL = "SELECT * 
				FROM masonryImage 
				WHERE is_active='Y'";
				
				$result = $dm->queryRecords($strSQL);	
				if ($result):
					while($row = mysqli_fetch_assoc($result)):
						echo '<div class="item"><img src="img/masonry/'.$row['url'] . '"></div>
						';
					endwhile;
				endif;
				?>				
				</div>
			</div>
		</div>

        </section><!-- end: #timeline -->

<div class="band-white band-top col-md-12"></div>
<div class="col-md-6">

<div class="google-map">
		<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2571.151454575606!2d-119.44197330000002!3d49.8771823!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x537d8cb869688ceb%3A0xc1f8f7fa58fa62a!2s2121+Springfield+Rd%2C+Kelowna%2C+BC+V1Y%2C+Canada!5e0!3m2!1sen!2s!4v1421822909894" width="100%" height="312" frameborder="0" style="border:0"></iframe>
</div>
</div>

<div class="col-md-6">		
        <div class="container txtblock nrthree">
            <p class="text-center lead">Our storefront is located in Central Kelowna, at the Mission Sports. We carry many samples of our products and lots of stock. <a href="#contact" class="scrollto">Come see us.</a></p>
        </div> 
</div>		
		<!-- end: container txtblock -->
<div class="band-white band-bottom col-md-12"></div>

<div class="col-md-12">       
        <section id="contact">
            <div class="container">
                <h2 class="text-center">Contact<i class="icon-contact"><img src="/images/icon-contact.png"></i></h2>
                <p class="text-center lead">Let`s get together and create something beautiful for you and your team.</p> 
                
                <div class="row"> 
                    <div class="col-md-7 contact-form" style="padding: 0 15px;"> 
                       <!-- <form role="form" name="contactform" method="post" action="contact.php" class="contact-form">-->
						<form role="form" action="contact.php" method="post" id="contactForm">
                            <input type="text" name="clientName" placeholder="Your name" required="" class="contact-subject">
                            <input type="email" name="email" placeholder="Your email address" class="contact-email" required="">
                            <textarea name="comments" cols="1" rows="5" placeholder="Message" required="" class="contact-message"></textarea>
							<div class="recaptcha-container" >
							<div class="g-recaptcha" style="  padding: 5px;"data-sitekey="6LcyEQgTAAAAALiYccvo95uESKNoFw0KgIU3cVd3"></div>
							</div>
                            <button name="send" type="submit" class="btn">Send</button>
                        </form>
                    </div> 
                    
                    <div class="col-md-5" style="text-align:left">
						<h3>Phone</h3>
						<p>1-888-584-3211</p>
						
						<h3>Email</h3>
						<p><a href="mailto: info@teamkits.net">info@teamkits.net</a></p>

                        <h3>Address</h3>
                        <address>
                            4-2121 Springfield Road<br>
                            Kelowna, BC<br>
                            Canada
                        </address>
                        
                        
                    </div> 
                </div><!-- end: .row -->
            </div><!-- end: .container -->
        </section><!-- end: #contact -->
</div>
<div class="band-white band-top col-md-12"></div>
      <!--  <section id="social">
            <div class="container">
                <ul class="inline text-center">
                    <li><a href="#" target="_blank"><i class="ico">F</i></a></li>
                    <li><a href="#" target="_blank"><i class="ico">L</i></a></li>
                    <li><a href="#" target="_blank"><i class="ico">E</i></a></li>
                    <li><a href="#" target="_blank"><i class="ico">O</i></a></li>
                    <li><a href="#" target="_blank"><i class="ico">D</i></a></li>
                </ul>            
            </div>
        </section> --><!-- end: #social -->

<div class="col-md-12">        
        <footer>
            <a href="#home" class="scrollto"><img src="/images/teamkits_logo_white.png" style="height:80px"></a>
		</footer>
		<div class="col-md-12" style="  background: #222;  text-align: right;  padding: 10px;  border-top: 1px solid #000;">
			<div class="container">
			<a href="https://orchardcity.ca"><img src="https://orchardcity.ca/images/orchardcity_logo_small_nb_white_letters.png" alt="Website by Orchard City Web Development" style="max-height:50px;"></a>		</div>
		</div>	
</div>			

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>  
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src="js/waypoints.min.js" type="text/javascript"></script>
		<script src="js/waypoints-sticky.js" type="text/javascript"></script>

        <script src="js/jquery.cycle2.min.js"></script>
        <script src="js/jquery.cycle2.scrollVert.min.js"></script>

		<script src="js/jquery.scrollto.js"></script>
		<script src="js/grid.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>	
		<script src="js/scripts.js"></script>
		<script>
         /*   $('#slider').cycle({
                fx : 'scrollVert',
                timeout: 3000,
                speed: 300,
                slides: '.slide'
            });*/

			$('.navbar').waypoint('sticky');

			$('a.scrollto, a#arrow_down').click(function(e){
				$('html,body').scrollTo(this.hash, this.hash);
				e.preventDefault();
			});

			$(function() {
				Grid.init();
			});		

		</script>
	<script src="js/Application.js"></script>	
            
</body></html>
<script>
// masonry
/*$(function() {	
	$('#masonry-content').masonry({
	  columnWidth: 200,
	  itemSelector: '.item'
	});
	*/
var $container = $('#masonry-content');
// initialize Masonry after all images have loaded  
$container.imagesLoaded( function() {
  $container.masonry({
	  columnWidth: 200,
	  itemSelector: '.item'
	});

});	
</script>
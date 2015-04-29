/******************************************************/
/*  JavaScript code for Orchard City Web Development  */
/*  Revised by Darren Walkoski - April 21, 2015       */
/******************************************************/



// Effects for the Brands page
function brandsEffects() {
	// Set initially viewable info
    $('#givova').addClass('selected');
    $('#givova-info').css('visibility','visible');
    $('#givova-info').animate({width:'toggle'},500);

    // Select all buttons
    $('.brands').click(function() {
    	// Remove the selected class from all of the brands
    	$('.brands').removeClass('selected');
    	// Add the clicked class to the currently selected brand
        $(this).addClass('selected');

        // Show the info panel
        // Hide all panels
        $('.brand-info').css('display','none');
    	// Show the selected info panel
        $('#'+this.id+'-info').css('visibility','visible');
        $('#'+this.id+'-info').animate({width:'toggle'},500);
    });
}



// Effects for the Products Page
function productsEffects() {
	// Set initially viewable info
    $('#kits').addClass('selected');
    $('#kits-info').css('visibility','visible');
    $('#kits-info').animate({width:'toggle'},500);

    // Select all buttons
    $('.products').click(function() {
    	// Remove the selected class from all of the brands
    	$('.products').removeClass('selected');
    	// Add the clicked class to the currently selected brand
        $(this).addClass('selected');

        // Show the info panel
        // Hide all panels
        $('.product-info').css('display','none');
    	// Show the selected info panel
        $('#'+this.id+'-info').css('visibility','visible');
        $('#'+this.id+'-info').animate({width:'toggle'},500);
    });
}


function googleMaps() {
	var loc = new google.maps.LatLng(49.877170,-119.441973);
	var marker;
	var map;
	var mapOptions = {
		    zoom: 16,
		    center: loc
	};

  	map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

  	marker = new google.maps.Marker({
		map: map,
		draggable: true,
		animation: google.maps.Animation.DROP,
		position: loc
  	});

  	var contentString =
  		'<div id="content">'+
		'<h3>Team Kits</h3>'+
		'<p>4-2121 Springfield Road<br>'+
		'Kelowna, BC, Canada<br>'+
		'1-888-584-3211</p>'+
		'</div>';

	var infowindow = new google.maps.InfoWindow({
		content: contentString,
		maxWidth: 220
	});

    infowindow.open(map,marker);
}


function initialize() {
	brandsEffects();
	productsEffects();

	googleMaps();
}
$(initialize);







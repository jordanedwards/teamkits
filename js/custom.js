/******************************************************/
/*  JavaScript code for Orchard City Web Development  */
/*  Revised by Darren Walkoski - May 2, 2015          */
/******************************************************/

// Get the name of the current page
var path = window.location.pathname;
path = path.substring(path.lastIndexOf('/') + 1);
var title = path.substring(0, path.lastIndexOf('.'));

// Define an array to hold the menu id names
var menu_names = ['#menu-1', '#menu-2', '#menu-3', '#menu-4', '#menu-5'];

// Set the currently selected page
function setCurrentPage(menu_name) {
    $(menu_name).css('color', '#262626');
}

// Check if the page is currently selected
function isSelected(id) {
    // If the page is currently selected, set the variable 'selected' to true
    if(((title == 'index' || title == '') && id == '#menu-1') ||
        (title == 'about' && id == '#menu-2') ||
        (title == 'brands' && id == '#menu-3') ||
        (title == 'products' && id == '#menu-4') ||
        (title == 'contact' && id == '#menu-5') ) {
        var selected = true;
    } else {
        var selected = false;
    }
    return selected;
}



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

    // Set current page
    for (var i=0; i <menu_names.length; i++) {
        if (isSelected(menu_names[i])) {
            setCurrentPage(menu_names[i]);
        }
    }
    
	brandsEffects();
	productsEffects();

	googleMaps();
}
$(initialize);







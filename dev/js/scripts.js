jQuery(document).ready(function() {
	$('.contact-form form input[type="text"], .contact-form form textarea').on('focus', function() {
			$('.contact-form form input[type="text"], .contact-form form textarea').removeClass('contact-error');
		});
	
	$('.contact-form form').submit(function(e) {
		e.preventDefault();
	    $('.contact-form form input[type="text"], .contact-form form textarea').removeClass('contact-error');
	    var postdata = $('.contact-form form').serialize();
	    $.ajax({
	        type: 'POST',
	        url: 'contact.php',
	        data: postdata,
	        dataType: 'json',
	        success: function(json) {
				//console.log(json);
	            if(json.emailMessage != '') {
	                $('.contact-form form .contact-email').addClass('contact-error').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            			$(this).removeClass('animated shake');
            		});
	            }
	           if(json.gRecaptchaResponse != '') {
	                $('.contact-form form .g-recaptcha').addClass('contact-error');
	            }
	            if(json.messageMessage != '') {
	                $('.contact-form form textarea').addClass('contact-error').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            			$(this).removeClass('animated shake');
            		});
	            }
	            if(json.emailMessage == '' && json.gRecaptchaResponse == '' && json.messageMessage == '') {
					//console.log("message sent");
	                $('.contact-form form').fadeOut('fast', function() {
	                    $('.contact-form').append('<p>Thanks for contacting us! We will get back to you very soon.</p>');
	                });
	            } else {
					$('#contactForm').prepend('<p class="contact-error">'+json.emailMessage+' '+json.gRecaptchaResponse+' '+json.messageMessage+'</p>');	
				}
	        }
	    });
	});
    
});
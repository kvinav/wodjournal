$.noConflict();

jQuery(document).ready(function($) {

	"use strict";

	[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
		new SelectFx(el);
	} );

	jQuery('.selectpicker').selectpicker;


	$('#menuToggle').on('click', function(event) {
		$('body').toggleClass('open');
	});

	$('.search-trigger').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		$('.search-trigger').parent('.header-left').addClass('open');
	});

	$('.search-close').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		$('.search-trigger').parent('.header-left').removeClass('open');
	});
	$(window).scroll(function() {
    if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
        $('#return-to-top').fadeIn(200);    // Fade in the arrow
    } else {
        $('#return-to-top').fadeOut(200);   // Else fade out the arrow
    }
	});
	$('#return-to-top').click(function() {      // When arrow is clicked
    	$('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
});

	$( "#selectList" ).change(function() {
  		var choice = $( "#selectList" ).val();
  		var wods = $('#wods');

  		var wodsTodo = $('#wodsTodo');
  		if (choice == 'Tous mes wods') {
  			wods.show();
  			wodsTodo.show();
  		
  		}else if (choice == 'Wods effectués') {
  			wods.show();
  			wodsTodo.hide();
  	
  		}else if (choice == 'A faire plus tard') {
  			wods.hide();
  			wodsTodo.show();
  		
  		}
	});

});
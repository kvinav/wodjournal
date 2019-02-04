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
	
	ajaxCall();
 	function ajaxCall(choice){
 		var choice = $('#selectList').val();
 		var userId = $('#userId').text();
		var list = $('#list');
		console.log(choice, userId);
		$.ajax({
			url: "http://127.0.0.1:8000/journal/list/service",
			method: "post",
			data: {'choice': choice, 'userId': userId},
			dataType : 'json',
		}).done(function(msg){
			console.log(msg);
			refreshList(msg);
		});
	}

	function refreshList(msg){
		list.innerHTML = "";
		$.each(JSON.parse(msg['data']), function (i, item){
			var li = document.createElement('li');
			var text = document.createTextNode(item.work + " " + item.time);
			li.appendChild(text);
			list.appendChild(li);
		});
	}
});


});
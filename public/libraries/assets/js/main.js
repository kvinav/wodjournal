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
	 $('.first-button').on('click', function () {

    $('.animated-icon1').toggleClass('open');
  });
  $('.second-button').on('click', function () {

    $('.animated-icon2').toggleClass('open');
  });
  $('.third-button').on('click', function () {

    $('.animated-icon3').toggleClass('open');
  });

  var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};


if(isMobile.any()) {
   $( "button" ).each(function( index ) {
  		$(this).removeClass('btn-lg');
	});
}
$(function() {
  $('select').selectric();
});

$(".like").on('click', function() {
  var like = $(this);
  like.attr('id', 'clicked');
  ajaxCall();
  function ajaxCall(choice){
    console.log('ajax1');
    var wodId = parseInt(like.attr('data-wod'));
    $.ajax({
      url: "http://127.0.0.1:8000/journal/like",
      method: "post",
      data: {wodId: wodId},
      dataType : 'json',
    }).done(function(msg){
      console.log('done1');
      $('#clicked').addClass('unlike');
      $('#clicked').removeClass('like');
      $('#clicked i').removeClass('far');
      $('#clicked i').addClass('fas');
      $('#clicked').removeAttr('id');

    });
  }

});
$(".unlike").on('click', function() {
  var unlike = $(this);
  unlike.attr('id', 'clicked');
  ajaxCall2();
  function ajaxCall2(choice){
    console.log('ajax2');
    var wodId = parseInt(unlike.attr('data-wod'));
    $.ajax({
      url: "http://127.0.0.1:8000/journal/unlike",
      method: "post",
      data: {wodId: wodId},
      dataType : 'json',
    }).done(function(msg){
      console.log('done2');
      $('#clicked').addClass('like');
      $('#clicked').removeClass('unlike');
      $('#clicked i').removeClass('fas');
      $('#clicked i').addClass('far');
      $('#clicked').removeAttr('id');

    });
  }

});

$("#btn-homepage").on('click', function() {
	window.location.href = window.location.pathname = '/inscription?email=' + $('#input-email-homepage').val();

});
   

});
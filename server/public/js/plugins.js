// Avoid `console` errors in browsers that lack a console.
"use strict";

$(document).ready(function(){
	
	
	//dragable mobile
	var drag;
	if($(window).width()<796){drag=false;}else{drag=true;}	
	
	/* Color Picker */
	
	  //demo
	 jQuery('.picker-btn').click(function(){
	  if(jQuery('.color-picker').css('right')=='0px'){
	   jQuery('.color-picker').animate({ "right": "-223px" }, "slow" );
	  }else{
	   jQuery('.color-picker').animate({ "right": "0px" }, "slow" );
	  }
	 });
    setTimeout(function(){
    jQuery('.color-picker').animate({ "right": "-223px" }, "slow" );}, 4000);
	
	var currentColor = 'blue';
	$('body').addClass(currentColor);

	$('.picker-blue').click(function(){
		$('body').removeClass(currentColor);
		$('body').addClass('blue');
		currentColor='blue';
		wpgmappity_maps_loaded();
	});
	$('.picker-black').click(function(){
		$('body').removeClass(currentColor);
		$('body').addClass('black');
		currentColor='black';
		wpgmappity_maps_loaded();
	});
	$('.picker-green').click(function(){
		$('body').removeClass(currentColor);
		$('body').addClass('green');
		currentColor='green';
		wpgmappity_maps_loaded();
	});
	$('.picker-yellow').click(function(){
		$('body').removeClass(currentColor);
		$('body').addClass('yellow');
		currentColor='yellow';
		wpgmappity_maps_loaded();
	});
	$('.picker-red').click(function(){
		$('body').removeClass(currentColor);
		$('body').addClass('red');
		currentColor='red';
		wpgmappity_maps_loaded();
	});
	$('.picker-turquoise').click(function(){
		$('body').removeClass(currentColor);
		$('body').addClass('turquoise');
		currentColor='turquoise';
		wpgmappity_maps_loaded();
	});
	$('.picker-purple').click(function(){
		$('body').removeClass(currentColor);
		$('body').addClass('purple');
		currentColor='purple';
		wpgmappity_maps_loaded();
	});
	$('.picker-orange').click(function(){
		$('body').removeClass(currentColor);
		$('body').addClass('orange');
		currentColor='orange';
		wpgmappity_maps_loaded();
	});
	$('.dark-version').click(function(){
		$('body').addClass('darker');
	});
	$('.light-version').click(function(){
		$('body').removeClass('darker');
	});

		
	/* End */
});
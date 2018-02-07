"use strict";

$(document).ready(function(){
	/*Validation*/	
	$("#contact").validate({
		submitHandler: function(form) {
			$(form).ajaxSubmit();
			$('.formSent').show();
		}
	});		
	
	/* Services RollOver Info */
	function loadServices(){
		$(".sl-ico").mouseenter(function(){
			$(this).parent().find(".s-roll").addClass('visible');
		});
		$(".sl-ico").mouseleave(function(){
			$(this).parent().find(".s-roll").removeClass('visible');
		});
	}
	
	/* Banner */
	function loadTall(){
		var altura = $(window).height();
		$('#home').css('height',altura);
	}
	
	/* Jump Menu */
	function loadJump(){
		$('.jump-menu').click(function() {
			if($('#nav2').hasClass('active')){
				$('#nav2').removeClass('active');
			}else{
				$('#nav2').addClass('active');
			}
		})
		
		$('#nav2 ul li a').click(function(){
			$('#nav2').removeClass('active');
		});
	}
	
	/* Scroll Up */ 
	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 1000);
		return false;
	});
	
	/* Parallax */
	function Move(seccio){
		$(seccio).each(function(){
			if($(this).attr('class')==='parallax'){
				$(this).css('background-position', '0 '+$(window).scrollTop()/3+'px');
			}else{
				$(this).css('background-position', '0 '+(($(window).scrollTop()+$(window).height()-$(this).attr('yPos'))/3+$(this).height())+'px');
			}
		});
	}
	
	/* Caroussel One Images & Two Queues */
	function loadCarousel(){
		
		/* Car One */
		var amplecar = $(".caroussel-list .car-img").length;
		var ampleitem = $(".car-img img").width;
		var amplelist = amplecar*ampleitem;
		$('.caroussel-list').css('width','amplelist')
	
		var itemscar=$('.caroussel-list .car-img').length;
		ampleitem=$('.caroussel').width();
		amplelist = itemscar*ampleitem;
		$('.caroussel-list').css('width', amplelist)
		//alert(amplelist);
		
		var fragment = document.createDocumentFragment(), 
		li = document.createElement('li');
		while (itemscar--) {
			fragment.appendChild(li.cloneNode(true));
		}
		$('.controller ul').append(fragment);
		
		var index = 0;
		var pos = 1;
		$('.controller ul li:first-child').addClass('selected');
		
		$(".controller ul li").click(function(){
			ampleitem=$('.caroussel').width();
			index = $(this).index();
			$(".caroussel").stop().animate({scrollLeft:ampleitem*index},'slow');
			 $('.controller ul li').removeClass('selected');
			$(this).addClass('selected');
			//alert(ampleitem);
		});
		$(".car-next").click(function(){
				if( index != $(".controller ul li").size()-1){
				  index++;
				  $(".caroussel").stop().animate({scrollLeft:ampleitem*index},'slow');
				  pos++;
				  $('.controller ul li.selected').removeClass('selected').next().addClass('selected');
			}
		   });
		$(".car-prev").click(function(){
		  if( index!=0 ){
			  index--;
			  $(".caroussel").stop().animate({scrollLeft:ampleitem*index},'slow');
			  pos--;
			  $('.controller ul li.selected').removeClass('selected').prev().addClass('selected');
		  }
		});
		
		/* Car Two */
		var amplecar2 = $(".caroussel-list-2 .car-quote").length;
		var ampleitem2 = $(".car-quote").width;
		var amplelist2 = amplecar2*ampleitem2;
		//alert(amplelist2);
		$('.caroussel-list-2').css('width','amplelist2')
		
		
		function bullets2(){
			var itemscar2=$('.caroussel-list-2 .car-quote').length;
			ampleitem2=$('.caroussel-2').width();
			amplelist2 = itemscar2*ampleitem2;
			$('.caroussel-list-2').css('width', amplelist2)
			//alert(amplelist);
			
			var fragment = document.createDocumentFragment(), 
			li = document.createElement('li');
			while (itemscar2--) {
				fragment.appendChild(li.cloneNode(true));
			}
			$('.controller-2 ul').append(fragment);
		}
		bullets2();
		
		var index2 = 0;
		var pos2 = 1;
		$('.controller-2 ul li:first-child').addClass('selected');
		
		$(".controller-2 ul li").click(function(){
			ampleitem2=$('.caroussel-2').width();
			index2 = $(this).index();
			$(".caroussel-2").stop().animate({scrollLeft:ampleitem2*index2},'slow');
			 $('.controller-2 ul li').removeClass('selected');
			$(this).addClass('selected');
			//alert(ampleitem);
		});
		$(".car-next-2").click(function(){
				if( index2 != $(".controller-2 ul li").size()-1){
				  index2++;
				  $(".caroussel-2").stop().animate({scrollLeft:ampleitem2*index2},'slow');
				  pos2++;
				  $('.controller-2 ul li.selected').removeClass('selected').next().addClass('selected');
			}
		   });
		$(".car-prev-2").click(function(){
		  if( index2!=0 ){
			  index2--;
			  $(".caroussel-2").stop().animate({scrollLeft:ampleitem2*index2},'slow');
			  pos2--;
			  $('.controller-2 ul li.selected').removeClass('selected').prev().addClass('selected');
		  }
		});
	}
	
	/* Arrow Animations */
	function loadArrows(){
		var fletxa=false;
		/* Car One */
		$('.car-prev').stop().animate({left:'-45px',opacity:'0'},"fast");
		$('.car-next').stop().animate({right:'-45px',opacity:'0'},"fast");
		$(".car-show-1 .show").mouseenter(function(){
			$('.car-prev').stop().animate({left:'-40px',opacity:'1'},"fast");
			$('.car-next').stop().animate({right:'-40px',opacity:'1'},"fast");
		}).mouseleave(function(){
			$('.car-prev').stop().animate({left:'-45px',opacity:'0'},"fast");
			$('.car-next').stop().animate({right:'-45px',opacity:'0'},"fast");
		});
		/* Car Two */
		$('.car-prev-2').stop().animate({left:'-45px',opacity:'0'},"fast");
		$('.car-next-2').stop().animate({right:'-45px',opacity:'0'},"fast");
		$(".car-show-2").mouseenter(function(){
			$('.car-prev-2').stop().animate({left:'-40px',opacity:'1'},"fast");
			$('.car-next-2').stop().animate({right:'-40px',opacity:'1'},"fast");
		}).mouseleave(function(){
			$('.car-prev-2').stop().animate({left:'-45px',opacity:'0'},"fast");
			$('.car-next-2').stop().animate({right:'-45px',opacity:'0'},"fast");
		});
	}
	
	/* Counter */
    $.fn.countTo = function(options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;
			
        return $(this).each(function() {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        })
    };
	
	/* Portfolio HoverDir Roll-Over */
	function mosaicInit() {
		$('.mosaic').mixitup({
			targetSelector: '.mosaic__item',
			filterSelector: '.mosaic__filter-item',
			effects: ['fade','scale'],
			easing: 'snap',
			transitionSpeed: 850,
		});
		$('.mosaic__item .image_item-meta--portfolio .image_item-table').each(function() {
			$(this).hoverdir();
		});
	}
	
	/* Slider AutoChanging Title */
	function loadTitleAnimated(){
		var myInterval;
		var contador = 1;
		var myFunc = function() {
			var cur = $('.main-title ul li').length;
			//alert(contador);
			if(cur == contador) {;
					$('.main-title ul li.t-current').removeClass('t-current');
					$('.main-title ul li').first().addClass('t-current');
					contador = 1;
				} else {
					contador++;
					$('.main-title ul li.t-current').removeClass('t-current').next().addClass('t-current');
				}
		};
		myInterval = setInterval(myFunc, 5000); // Set Animation Interval in Miliseconds
	}

	/* Main Menu Section Selector */
	function loadMenuSelector(){
		$('#nav').onePageNav({
			begin: function() {
			console.log('start');
			},
			end: function() {
			console.log('stop');
			},
		scrollOffset: 75 // header Height
		});
	}


	/* Small Funtions */
	function loadSmall(){
		
		/* Portfolio Animate Show Project */
		/*$('.ch-grid').click(function() {
			$("html, body").animate({ scrollTop: $('#project-show').offset().top }, 1000);
		});*/
			
		/* Call LightBox Featured Works */
		$('.image-link').magnificPopup({type:'image'});
		$('.ca-wrapper').magnificPopup({
		  delegate: 'a', // child items selector, by clicking on it popup will open
		  type: 'image',
		  // other options
		  gallery:{
			enabled:true
		  }
		});
	}
	


	/* Isotope/ Portfolio Filter PlugIn */    
	var container = $('#i-portfolio');	
	container.isotope({
		animationEngine : 'best-available',
		animationOptions: {
			duration: 200,
			queue: false
		},
		layoutMode: 'fitRows'
	});	
	// filter items when filter link is clicked
	$('#filters a').click(function(){
		$('#filters a').removeClass('active');
		$(this).addClass('active');
		var selector = $(this).attr('data-option-value');
		container.isotope({ filter: selector });
		setProjects();		
		return false;
	});
	
	function splitColumns() { 
	var winWidth = $(window).width(), 
		columnNumb = 1;
	if (winWidth > 1200) {
		columnNumb = 5;
	} else if (winWidth > 900) {
		columnNumb = 4;
	} else if (winWidth > 600) {
		columnNumb = 3;
	} else if (winWidth > 300) {
		columnNumb = 1;
	}
	return columnNumb;
	}		
	
	function setColumns() { 
	var winWidth = $(window).width(), 
		columnNumb = splitColumns(), 
		postWidth = Math.floor(winWidth / columnNumb);
	container.find('.element').each(function () { 
		$(this).css( { 
			width : postWidth + 'px' 
		});
		
		var marhei = ($(this).find('div').height()/2)-49;
		
		$(this).find('div > span').css( { 
			margin : marhei+'px 20px' 
		});
		
	});
	}		
	function setProjects() { 
	setColumns();
	container.isotope('reLayout');
	}			
	
	function loadIsotope(){
	container.imagesLoaded(function () {setProjects();});
	setProjects();
	}
	
	/* Call HoverDir Portfolio RollOver */
	function loadHoverDir(){
		$(' #i-portfolio > .ch-grid ').each( function() { $(this).hoverdir({
			hoverDelay : 5
		}); } );
	}
	
	/* Logos/Company Carousel */
	function loadLogos(){
		$('#logos').carouFredSel({
			responsive: true,
			width: '100%',
			scroll: 1,
			items: {
				width: 400,
			//	height: '30%',	//	optionally resize item-height
				visible: {
					min: 1,
					max: 6
				}
			}
		});	
	}
	
	/* Call Featured Carousel Container */
	function loadContentCarousel(){
		$('#ca-container').contentcarousel();
	}
	
	
	////////////////////////////////////////////
	
	/* Scroll */
	$(window).bind("scroll", function(){//when the user is scrolling...
	
		/* Parallax */
		Move('.paraOn'); //move the background images in relation to the movement of the scrollbar
		
		/* Scroll Top Btn */
		if ($(this).scrollTop() > $(window).height()-1) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut();
		}
	});
	
	/* Resize */
	function resizedw(){
	// Haven't resized in 500ms!
		setProjects();
		loadTall();
	}
	
	var doit;
	$(window).bind('resize', function () { 
		clearTimeout(doit);
		doit = setTimeout(resizedw, 1000);
	});		
	function loadsuperslides(){
      $('#slides-1').superslides({
        hashchange: false,
        animation: 'fade',
		play: 10000,
		pagination: false
      });
	}
	
	function loadbxSlider(){
	  $('.bxslider').bxSlider();
	}
	
	/* Inview */
	function loadInview(){
		/* Parallax */
		$('.parallax').bind('inview', function (event, visible) {
			if (visible === true) {
			// element is now visible in the viewport
			var offset = $(this).offset();
			$(this).addClass('paraOn').attr('yPos',offset.top);
			} else {
			// element has gone out of viewport
			$(this).removeClass('paraOn');
			}
		});
		
		/* Fade In Elements */
		$('.hideme').bind('inview', function (event, visible) {
			if (visible === true) {
				var offset = $(this).offset();
				$(this).removeClass('hideme');
			}
		});
		$('.p-image-02').bind('inview', function (event, visible) {
			if (visible === true) {
				$('.dontHide').removeClass('hideme-slide');
			}
		});

		$('.newtr').bind('inview', function (event, visible) {
			if (visible === true) {
				$('.dontHide').removeClass('hideme-slide2');
			}
		});


		
		/* Facts Counter */
		var count=0;
		var dataperc;
		
		//mobile counter
	
			$('.milestone-counter').bind('inview', function (event, visible) {
				if (visible === true & count===0) {
				// element is now visible in the viewport
				count++;
				$('.milestone-counter').each(function(){
					dataperc = $(this).attr('data-perc'),
					$(this).find('.milestone-count').delay(6000).countTo({
					from: 0,
					to: dataperc,
					speed: 2500,
					refreshInterval: 80
					});
				});
				} else {
				// element has gone out of viewport
				}
			});
		
		/* Skills Animation */
		var value;
		$('.skill-in').bind('inview', function (event, visible) {
			if (visible === true) {
			// element is now visible in the viewport
			$(this).each(function(){
				value = $(this).attr('title');
				$(this).animate({ "width": value+'%' }, 2000);
			});
			}
		});
	}
	
	/* Load Functions */
	loadServices();
	//loadTall();
	loadJump();
	
	if($(window).width()>974){	
		loadInview();
	}else{
		$('.milestone-count.highlight').each(function(){
			$(this).html($(this).parent().attr('data-perc'))
		})

		
		var value;
		$('.skill-in').each(function(){
			value = $(this).attr('title');
			$(this).css("width", value+'%');
		});
	}
	
	loadCarousel();
	loadArrows();
	loadSmall();
	loadTitleAnimated();
	loadMenuSelector();
	loadIsotope();
	loadHoverDir();
	loadLogos();
	loadContentCarousel();
	//loadbxSlider();
	loadsuperslides();
});
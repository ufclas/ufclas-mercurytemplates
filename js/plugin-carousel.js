jQuery(document).ready(function ($) {

    // GLOBAL NAVIGATION 

    // Optional Offsite Links add to mobile
    jQuery('.offsite-links a').each(function (index, value) {
        var mobileOffsite = jQuery(this).clone();
        jQuery('.mobile-offsite-links').append(mobileOffsite);
    });
    
	var hrAdded = false;
	jQuery('.audience-nav-links .dropdown').each(function(index, value) {
		var mobileLinks = jQuery(this).clone().addClass('position-static');
	
		if (mobileLinks.length) {
			jQuery('.mobile-secondary-dropdown').append(mobileLinks);
			jQuery('.mobile-secondary-dropdown').find('.dropdown-hover > .nav-link').attr('data-bs-toggle', 'dropdown');
	
			// Check if <hr /> has not been added yet
			if (!hrAdded) {
				jQuery('.mobile-secondary-dropdown').prepend('<hr />');
				hrAdded = true; // Set the flag to true to indicate <hr /> has been added
			}
		}
	});

    jQuery('.mobile-secondary-dropdown .menu-item ').each(function (index, value) {
        var currentID = jQuery(this).attr('id');
        var newID = currentID + '-mobile';
        jQuery(this).attr('id', '');
    });


        jQuery('.mobile-secondary-dropdown .dropdown-toggle-top').each(function (index, value) {
        var currentID = jQuery(this).attr('id');
        var newID = currentID + '-mobile';
        jQuery(this).attr('id', newID);
    });

    jQuery('.mobile-secondary-dropdown .dropdown-menu').each(function (index, value) {
        var currentID = jQuery(this).attr('aria-labelledby');
        var newID = currentID + '-mobile';
        jQuery(this).attr('aria-labelledby', newID);
    });



    // Desktop Navigation Dropdown Offset
    jQuery(".header .dropdown-toggle").on("mouseover focus", function (event) {
        if (jQuery(window).width() > 1055) {
            var liparent = jQuery(this.parentElement);
            var xOffset = liparent.offset().left;
            var something = jQuery(this).next().next();

            if (xOffset > 700) {
                jQuery(this).next().next().css("left", "unset");
            }
        }
    });

    jQuery(".header .dropdown-toggle").on("click", function (event) {
    if (jQuery(window).width() < 1055) {
            event.preventDefault();
            if (jQuery(this).hasClass("show")) {
                var linkUrl = jQuery(this).attr('href');
                window.location.href = linkUrl;
            } else {
                jQuery('.header .navbar-nav').find('.dropdown .show').removeClass('show');
                jQuery(this).toggleClass('show');
                jQuery(this).next().next().toggleClass('show');
                jQuery(this).next().addClass('toggled');
            } 
    } 
}); // Go To Link on Second Click On Expanded Dropdown

    jQuery(".mobile-menu-toggle").on("click", function (event) {
        if (jQuery(this).prev().hasClass("show")) {
            //MENU OPEN WHEN CLICKED
            jQuery(this).prev().removeClass('show');
            jQuery(this).next().removeClass('show');
            jQuery(this).removeClass('toggled');
        } else {
            //MENU WAS CLOSED WHEN CLICKED
            jQuery('.header .navbar-nav').find('.dropdown .show').removeClass('show');
            jQuery(this).prev().addClass('show');
            jQuery(this).next().addClass('show');
            jQuery(this).addClass('toggled');


        }
    });







       // On Resize  Header Events
       jQuery(window).on('resize', function () {
        if (jQuery(window).width() < 1055) {
            jQuery('.header .dropdown-toggle').attr('data-bs-toggle', 'dropdown');
        } else {
            jQuery('.header .dropdown-toggle').removeAttr('data-bs-toggle');
        }
    });



    // On Scroll Events 


    // Progress Footer Bar 
    jQuery(window).scroll((function () {
            
         // Progress Footer Bar 
        let w = (document.body.scrollTop || document.documentElement.scrollTop) / (document.documentElement.scrollHeight - document.documentElement.clientHeight) * 100;
        jQuery("#footerTopBorder").css({
            width: w + "%"
        });

         // Parallax Functions
        featcardParallax();
        fullwidthParralax();
    }));



    // Text Image Showcase

    // Prevent last 'Paralax' from sticking
    jQuery('.showcase-text-image').last().css('position', 'relative');



    // Visual Navigation - Directional Hover
    jQuery('.dh-container').directionalHover();




    // HERO Block 
    // Section trigger hover state on focus 
    jQuery('.hero__text .button').on('focus keyup', function (e) {
        jQuery('.hero__text').addClass('hero_text_hover');
    });
    jQuery('.hero__text .button').on('keydown', function (e) {
        jQuery('.hero__text').removeClass('hero_text_hover');
    });

    // HERO SECTION VERTICAL REDLINE PARALLAX ON SCROLL
    jQuery(".verScroll_Col").prependTo(jQuery(".hero-wrapper"));
    if (jQuery("#object1").length > 0) {
        jQuery('#object1').css({
            'top': 45 + 'px'
        });
        var window_height1 = jQuery(window).height() - jQuery('#object1').height() + 250;
        var document_height1 = jQuery('.hero-wrapper').height() - jQuery('#object1').height();

        jQuery(function () {
            jQuery(window).scroll(function () {
                var scroll_position1 = jQuery(window).scrollTop();
                var object_position_top1 = window_height1 * (scroll_position1 / document_height1) + 45;
                jQuery('#object1').css({
                    'top': object_position_top1
                });
            });
        });
    }
    if (jQuery("#object2").length > 0) {
        jQuery('#object2').css({
            'top': 120 + 'px'
        });
        var window_height2 = jQuery(window).height() - jQuery('#object2').height();
        var document_height2 = jQuery('.hero-wrapper').height() - jQuery('#object2').height();
        jQuery(function () {
            jQuery(window).scroll(function () {
                var scroll_position2 = jQuery(window).scrollTop();
                var object_position_top2 = window_height2 * (scroll_position2 / document_height2) + 120;
                jQuery('#object2').css({
                    'top': object_position_top2
                });
            });
        });
    }
    jQuery('#play-video').on('click', function () {
        jQuery('#hero-video').get(0).play();
    });
    jQuery('#pause-video').on('click', function () {
        jQuery('#hero-video').get(0).pause();
    });

    var video = jQuery("#hero-video")[0];
    var playButton = jQuery("#play-video");
    var pauseButton = jQuery("#pause-video");

    // Show the play button and hide the pause button initially

    playButton.hide();
    pauseButton.hide();

    if (video) {
        pauseButton.show();
        pauseButton.css('opacity', '1');
        playButton.hide();

        // Add click event listener to play button
        playButton.on("click", function () {
            video.play();
            playButton.hide();
            pauseButton.css('opacity', '1');
            pauseButton.show().focus();
        });

        // Add click event listener to pause button
        pauseButton.on("click", function () {
            video.pause();
            pauseButton.hide();
            playButton.css('opacity', '1');
            playButton.show().focus();
        });

        // Add event listener to detect video state changes
        video.addEventListener("play", function () {
            pauseButton.css('opacity', '1');
            pauseButton.show();
            playButton.hide();
        });

        video.addEventListener("pause", function () {
            playButton.css('opacity', '1');
            playButton.show();
            pauseButton.hide();
        });
    }


    function SlickEventShell(){
		// Event Shell SLICK SLIDER
		jQuery('.event-shell-carousel').slick({
		  slidesToShow: 3,
		  slidesToScroll: 1,
		  arrows: true,
		  dots: false,
		  speed: 300,
		  infinite: true,
		  autoplaySpeed: 5000,
		  autoplay: false,
		  accessibility: true,
		  prevArrow: '<button class="carousel-control-prev" type="button"><span class="carousel-control-prev-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="40.218" height="113.232"><path fill="#343741" d="M32.883 0h7.15L7.144 56.361l33.073 56.871h-7.15L-.001 56.457Z" data-name="Path 9473"/></svg></span><span class="visually-hidden">Previous</span></button>',
		  nextArrow: '<button class="carousel-control-next" type="button"><span class="carousel-control-next-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="40.218" height="113.232" data-name="Group 10015"><path fill="#343741" d="M7.334 0H.184l32.889 56.361L0 113.232h7.15l33.068-56.775Z" data-name="Path 9473"/></svg></span><span class="visually-hidden">Next</span></button>',
		  responsive: [{
			breakpoint: 991,
			settings: {
			  slidesToShow: 2,
			}
		  },
		  {
			  breakpoint: 767,
			  settings: {
				slidesToShow: 1,
			  }
			}]
		});
	  }
	  function pollSlickEventShell(i){
		  i++;
		  if (i >= 100){}
		  else if(jQuery('.upcoming-events-item').length > 0){
			  SlickEventShell();
		  }
		  else{
			  setTimeout(function(){
				  pollSlickEventShell(i);
			  }
			  , 50);
			 }
	  }
  
	  pollSlickEventShell(0);
        // End Event Shell

        

    // Faculty Listing
    jQuery('.faculty-listing-carousel').not('.slick-initialized').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        speed: 300,
        infinite: true,
        autoplaySpeed: 5000,
        autoplay: false,
        accessibility: true,
        prevArrow: '<button class="carousel-control-prev" type="button"><span class="carousel-control-prev-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="40.218" height="113.232"><path fill="#D8D8D6" d="M32.883 0h7.15L7.144 56.361l33.073 56.871h-7.15L-.001 56.457Z" data-name="Path 9473"/></svg></span><span class="visually-hidden">Previous</span></button>',
        nextArrow: '<button class="carousel-control-next" type="button"><span class="carousel-control-next-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="40.218" height="113.232" data-name="Group 10015"><path fill="#D8D8D6" d="M7.334 0H.184l32.889 56.361L0 113.232h7.15l33.068-56.775Z" data-name="Path 9473"/></svg></span><span class="visually-hidden">Next</span></button>',
        responsive: [{
            breakpoint: 1055,
            settings: {
                slidesToShow: 3,
            }
        }, {
            breakpoint: 991,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 1,
            }
        }
        ]
    });

    setTimeout(function () {
        jQuery('.faculty-listing-item').attr('tabindex', '-1');
    }, 200);


    // On before slide change
    jQuery('.faculty-listing-carousel').on('init afterChange beforeChange', function (event, slick, currentSlide, nextSlide) {
        setTimeout(function () {
            jQuery('.faculty-listing-carousel .slick-active').attr('tabindex', '-1');
        }, 200);
    });

    // End Faculty Listing




    // Faculty Landing Block 
    function facultyLanding() {
        if (jQuery(window).width() > 767) {
            jQuery(".faculty-page .faculty-listing-item").show();
            jQuery("#facultyLoadmore").hide();
        } else {
            if (jQuery(".faculty-row").hasClass("load-more-touched")) {
                return;
            } else {
                jQuery(".faculty-page .faculty-listing-item").hide().slice(0, 4).show();
                jQuery("#facultyLoadmore").show();
            }
        }
    }
    facultyLanding();

    // Resize Functions
    jQuery(window).on('resize', function () {
        facultyLanding();
    });


    jQuery("#facultyLoadmore").click(function (event) {
        event.preventDefault();
        var facRows = jQuery('.faculty-listing-item:hidden');
        jQuery(".faculty-row").addClass('load-more-touched');
        jQuery(facRows).slice(0, 4).show();
        var facRows = jQuery('.faculty-listing-item:hidden');

        if (facRows.length >= 1) {
            setTimeout(function () {
                jQuery('#facultyLoadmore').attr('tabindex', '0');
                jQuery('#facultyLoadmore').show();
            }, 200);
        } else {
            setTimeout(function () {
                jQuery('#facultyLoadmore').attr('tabindex', '-1');
                jQuery('#facultyLoadmore').hide();
            }, 200);
        }
    });

    jQuery("#facultyLoadmore").on("click", function (event) {
        jQuery(this).trigger("blur");
        jQuery(this).removeClass('hovered');
        jQuery(this).trigger("focusout");
    });

        // End Faculty Landing Block 

            // Faculty Hover
    jQuery(".news-hero-featured").hover(function (e) {
        jQuery(this).addClass('hovered');
    }, function (a) {
        jQuery(this).removeClass('hovered');
    });

    jQuery(".hero-link").hover(function (e) {
        jQuery(this).addClass('hovered');
    }, function (a) {
        jQuery(this).removeClass('hovered');
    });

    jQuery(".news-hero-featured").on("click", function (event) {
        jQuery(this).trigger("blur");
        jQuery(this).removeClass('hovered');
        jQuery(this).trigger("focusout");
        jQuery('.hero-link').trigger("blur");
        jQuery('.hero-link').removeClass('hovered');
        jQuery('.hero-link').trigger("focusout");
    });

    jQuery(".hero-link").on("click", function (event) {
        jQuery(this).trigger("blur");
        jQuery(this).removeClass('hovered');
        jQuery(this).trigger("focusout");
        jQuery('.hero-link').trigger("blur");
        jQuery('.hero-link').removeClass('hovered');
        jQuery('.hero-link').trigger("focusout");
    });



    // Faculty Hover
    jQuery(".faculty-listing-img").hover(function (e) {
        jQuery(this).addClass('hovered');
    }, function (a) {
        jQuery(this).removeClass('hovered');
    });


    jQuery(".faculty-listing-img").on("click", function (event) {
        jQuery(this).trigger("blur");
        jQuery(this).removeClass('hovered');
        jQuery(this).trigger("focusout");
    });





    // Content Carousel Slider

    jQuery('.content-carousel-slide').not('.slick-initialized').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        dots: true,
        autoplaySpeed: 5000,
        rtl: false,
        autoplay: false,
        accessibility: true,
        prevArrow: '<button class="carousel-control-prev" type="button"><span class="carousel-control-prev-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="40.218" height="113.232"><path fill="#fff" d="M32.883 0h7.15L7.144 56.361l33.073 56.871h-7.15L-.001 56.457Z" data-name="Path 9473"/></svg></span><span class="visually-hidden">Previous</span></button>',
        nextArrow: '<button class="carousel-control-next" type="button"><span class="carousel-control-next-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="40.218" height="113.232" data-name="Group 10015"><path fill="#fff" d="M7.334 0H.184l32.889 56.361L0 113.232h7.15l33.068-56.775Z" data-name="Path 9473"/></svg></span><span class="visually-hidden">Next</span></button>',
    });

    setTimeout(function () {
        jQuery('.carousel-inner .slick-track .slick-slide').attr('tabindex', '-1');
        jQuery('.slick-dots button').attr('tabindex', '0');
    }, 200);


    // On before slide change
    jQuery('.carousel-inner').on('init afterChange beforeChange', function (event, slick, currentSlide, nextSlide) {
        setTimeout(function () {
            jQuery('.carousel-inner .slick-track .slick-active').attr('tabindex', '-1');
            jQuery('.slick-dots button').attr('tabindex', '0');
        }, 200);
    });

    // END Content Carousel Block


    // Card Text Only Block
    jQuery('.card-container').not('.slick-initialized').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        speed: 300,
        infinite: true,
        autoplaySpeed: 5000,
        autoplay: false,
        accessibility: true,
        prevArrow: '<button type="button" role="presentation" class="cto-prev"><span aria-label="Previous">‹</span></button>',
        nextArrow: '<button type="button" role="presentation" class="cto-next"><span aria-label="Next">›</span></button>',
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
            }
        },]
    });
    // End Card Text Only Block



    // Featured Cards Block
    jQuery('.featured-card-container').not('.slick-initialized').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        speed: 300,
        infinite: false,
        autoplaySpeed: 5000,
        autoplay: false,
        accessibility: true,
        prevArrow: '<button type="button" role="presentation" class="cto-prev"><span aria-label="Previous">‹</span></button>',
        nextArrow: '<button type="button" role="presentation" class="cto-next"><span aria-label="Next">›</span></button>',
        responsive: [{
            breakpoint: 1024,
            settings: {
                infinite: true,
                slidesToShow: 1,
            }
        },]
    });
    // End Featured Cards Block




    // Content Carousel Stacked
    jQuery('.stacked-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: true,
        infinite: true,
        arrows: true,
        dots: false,
        autoplaySpeed: 5000,
        autoplay: false,
        accessibility: true,
        prevArrow: '<button type="button" role="presentation" class="stacked-prev"><span aria-label="Previous"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 62 60"><path class="svg-bg" fill="#f3f1e9" d="M62 60H30a30 30 0 0 1 0-60h32Z"/><path class="svg-arrow" d="M34.075 45.338h2.708L27.874 30.07l8.959-15.406h-2.712l-8.958 15.38Z"/></svg></span></button>',
        nextArrow: '<button type="button" role="presentation" class="stacked-next"><span aria-label="Next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 62 60"><path class="svg-bg" fill="#f3f1e9" d="M0 0h32a30 30 0 0 1 0 60H0Z"/><path class="svg-arrow" d="M27.925 14.662h-2.708l8.909 15.268-8.959 15.406h2.712l8.958-15.38Z"/></svg></span></button>',
        fade: true,
        appendArrows: ".stacked-arrow-wrapper",
    });

	jQuery('.stacked-slider').on('init afterChange', function(event, slick, direction) {

		// remove all prev/next
		slick.$slides.removeClass('prevdiv').removeClass('nextinStack').removeClass('secondinStack').removeClass('thirdinStack');
		slick.$slides.attr('tabindex', '-1');
		// find current slide
		for (var i = 0; i < slick.$slides.length; i++) {
			var $slide = $(slick.$slides[i]);
			if ($slide.hasClass('slick-current')) {
				jQuery(this).attr('tabindex','-1');
				// update DOM siblings
				$slide.prev().addClass('prevdiv');
				$slide.next().addClass('nextinStack');
				$slide.next().next().addClass('secondinStack');
				$slide.next().next().next().addClass('thirdinStack');
				break;
			}
		}
		setTimeout(function () {
            jQuery('.stacked-slider .slick-track').find('.slick-active').attr('tabindex', '-1');
        }, 200);
	});

	jQuery('.stacked-slider .slick-track').find('.slick-active').attr('tabindex', '-1');


    // End Content Carousel - Stacked 





    // Image Gallery 

    // Shift Focus to Close Button Modals
    jQuery(".modal").on('shown.bs.modal', function () {
        jQuery(this).find('.btn-close').focus();
    });
    var modalDivs = jQuery("div.img-gallery-modal");
    // Move each modal div to the end of the body element
    modalDivs.each(function () {
        jQuery(this).appendTo("body");
    });

    jQuery('.image-gallery .carousel-inner').not('.slick-initialized').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        autoplaySpeed: 5000,
        autoplay: false,
        accessibility: true,
        prevArrow: '<button class="carousel-control-prev" type="button"><span class="carousel-control-prev-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="40.218" height="113.232"><path fill="#D8D8D6" d="M32.883 0h7.15L7.144 56.361l33.073 56.871h-7.15L-.001 56.457Z" data-name="Path 9473"/></svg></span><span class="visually-hidden">Previous</span></button>',
        nextArrow: '<button class="carousel-control-next" type="button"><span class="carousel-control-next-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="40.218" height="113.232" data-name="Group 10015"><path fill="#D8D8D6" d="M7.334 0H.184l32.889 56.361L0 113.232h7.15l33.068-56.775Z" data-name="Path 9473"/></svg></span><span class="visually-hidden">Next</span></button>',
    });

    jQuery(".img-gallery-modal").on('hidden.bs.modal', function () {

        jQuery(".col-image-gallery a").trigger("blur");
        jQuery(".col-image-gallery a").removeClass('hovered');
        jQuery(".col-image-gallery a").trigger("focusout");
    });

    jQuery(document).ready(function () {
        var isMouseClick = false;
        // Handle click event
        jQuery('.col-image-gallery a').click(function () {
            isMouseClick = true;
            jQuery('.col-image-gallery a').removeClass('visible-outline');
            jQuery(this).removeClass('visible-outline');
        });

        // Handle focus event
        jQuery('.col-image-gallery a').focus(function () {
            if (!isMouseClick) {
                jQuery('.col-image-gallery a').removeClass('visible-outline');
                jQuery(this).addClass('visible-outline');
            }
            isMouseClick = false;
        });

        // Handle click event
        jQuery('.image-gallery .slick-arrow').click(function () {
            isMouseClick = true;
            jQuery('.col-image-gallery a').removeClass('visible-outline');
        });

        // Handle focus event
        jQuery('.image-gallery .slick-arrow').focus(function () {
            if (!isMouseClick) {
                jQuery('.col-image-gallery a').removeClass('visible-outline');
            }
            isMouseClick = false;
        });

    });

    // End Image Gallery 



    // Tab Block
    jQuery('.tab-block').each(function (index, value) {
        jQuery('.tab-link').each(function (index, value) {
            var currentSnippet = jQuery(this).clone();
            jQuery('#tab-block').append(currentSnippet);
        });
        jQuery(this).find('.desktop-tab-link-col .tab-link').first().removeClass('collapsed');
        jQuery(this).find('.tab-content .tab-link').first().removeClass('collapsed');
        jQuery(this).find('.tab-content .tab-link').first().removeClass('collapsed');
        jQuery(this).find('.tab-content .accordion-item').find('.accordion-collapse').first().addClass('show');
        var dataParent = jQuery('.tab-content').attr('id');
        jQuery(this).find('.accordion-collapse').attr('data-bs-parent', '#' + dataParent);

        jQuery('.desktop-tab-link-col .tab-link').each(function (index, value) {
            jQuery(this).attr('id', '');
        });
    });

        // End Tab Block

        // Accordion Block
        jQuery('.accordion').each(function (index, value) {
            if (jQuery('.accordion.closeAll').length == 0) {
                jQuery(this).find('.accordion-button').first().removeClass('collapsed');
                jQuery(this).find('.accordion-item').find('.accordion-collapse').first().addClass('show');
            }

            var dataParent = jQuery(this).attr('id');
            jQuery(this).find('.accordion-collapse').attr('data-bs-parent', '#' + dataParent);
        });
    
    
        //Accordion ScrollTo
        jQuery('.accordion-header').on('click', function () {
            var element = jQuery(this);
            if (jQuery(window).width() < 800) {
                setTimeout(function () {
                    jQuery('html, body').animate({
                        scrollTop: element.offset().top - 100
                    }, 10);
                }, 400);
            }
        });
    

    jQuery(document).ready(function () {
        var isMouseClick = false;
        // Handle click event
        jQuery('.accordion-button').click(function () {
            isMouseClick = true;
            jQuery(this).removeClass('visible-outline');
        });

        // Handle focus event
        jQuery('.accordion-button').focus(function () {
            jQuery(this).addClass('visible-outline');
            if (!isMouseClick) {
                jQuery('.accordion-button').removeClass('visible-outline');
                jQuery(this).addClass('visible-outline');
            }
            isMouseClick = false;
        });

        // Handle focusout event
        jQuery('.accordion-button').on('focusout', function () {
            jQuery('.accordion-button').removeClass('visible-outline');
        });

        // Handle keypress event
        jQuery('.accordion-button').keypress(function (event) {
            if (event.which === 13 || event.keyCode === 13) {
                jQuery('.accordion-button').removeClass('visible-outline');
                jQuery(this).addClass('visible-outline');
                isMouseClick = false;
            }
        });
    });


            //  End Accordion Block



    // Search - Shift Focus On open
    jQuery(".off-canvas-search").on('shown.bs.offcanvas', function () {
        setTimeout(function () {
            jQuery('.offcanvas .form-control').trigger('focus');
        }, 200);
    });


        // Handle keypress event
        jQuery('#mobile-search-icon').keypress(function (event) {
            if (event.which === 13 || event.keyCode === 13) {
                jQuery('.navbar-search').trigger('click');

            }
        });
    
    // End Search



    // Cards Text Image - Image Zoom On Button Hover
    jQuery(".col-cti .animated-border-button").on('mouseover focus', function () {
        jQuery(this).siblings('.image-box').find('.card-image').css('transform', 'scale(1.1)');
    });
    jQuery(".col-cti .animated-border-button").on('mouseout focusout', function () {
        jQuery(this).siblings('.image-box').find('.card-image').css('transform', 'scale(1)');
    });
    // End Cards Text Image


    // Featured Cards Text Image - Image Zoom On Button Hover
    jQuery(".featured-card").on('mouseover focus', function () {
        jQuery(this).find('.featured-card-image').css('transform', 'scale(1.1)');
    });
    jQuery(".featured-card").on('mouseout focusout', function () {
        jQuery(this).find('.featured-card-image').css('transform', 'scale(1)');
    });
    // End Featured Cards Text Image


    jQuery('.multiple-video-container').each(function() {
        var container = jQuery(this); // Store the reference to the current .multiple-video-container element
        container.find('.video-load-more').attr('tabindex', '0');
        container.find('.video-row').first().css('display', 'flex');
        var vidRows = container.find('.video-row');
        var clickRows = container.find('.video-row:hidden');
    
        if (vidRows.length >= 2) {
            container.find('.video-load-more').show();
        } else {
            container.find('.video-load-more').hide();
        }
    
        function performAction() {
            var clickRows = container.find('.video-row:hidden'); // Use the stored reference to the container
            if (clickRows.length >= 1) {
                setTimeout(function() {
                    clickRows.first().css('display', 'flex'); // Use the stored reference to the clickRows
                }, 200);
            }
            if (clickRows.length > 1) {
                setTimeout(function() {
                    container.find('.video-load-more').show(); // Use the stored reference to the container
                }, 200);
            } else {
                setTimeout(function() {
                    container.find('.video-load-more').hide(); // Use the stored reference to the container
                }, 200);
            }
        }
    
        // Add event listener for the button click event
        container.find(".video-load-more").click(function(event) {
            event.preventDefault();
            performAction();
        });
    
        // Add event listener for the Enter key press event
        container.find(".video-load-more").keypress(function(event) {
            if (event.which === 13 || event.keyCode === 13) {
                event.preventDefault();
                performAction();
            }
        });
    });
    




    // SECTION NAVIGATION

    jQuery(".section-menu-btn").keypress(function (event) {
        if (event.which === 13 || event.keyCode === 13) {
            event.preventDefault();
            jQuery(this).trigger('click');
        }
    });


    jQuery(".section-menu-btn").on('click', function (event) {
        event.preventDefault();
        var currentPage = jQuery('#section-nav-ul .current-page');
        jQuery(this).toggleClass('menu-enabled');
        jQuery(this).parent().toggleClass('menu-active');
        jQuery('#section-nav-ul a').first().focus();
        if (jQuery(".section-menu-btn").hasClass("menu-enabled")) {
            jQuery('#section-nav-ul').find('.toggleExpanded').removeClass('toggleExpanded');
            jQuery('#section-nav-ul').find('.dropdown-active').removeClass('dropdown-active');
            jQuery('#section-nav-ul').find('.show').removeClass('show');
            jQuery('#section-nav-ul').find('.menu-out').removeClass('menu-out');
        }

        setSectionNavigation();
    
        // jQuery('#section-nav-ul .current_page_ancestor').each(function (index, value) {
        //     jQuery(this).children().first().trigger('click');
        // });
    });

    jQuery(".section-navigation-back").on('click', function (event) {
        event.preventDefault();
        var activeDt = jQuery('#section-nav-ul').find('.toggleExpanded').last().prev();
        var activeDd = jQuery('#section-nav-ul').find('.toggleExpanded').last();
        jQuery(activeDd).removeClass('show toggleExpanded');
        jQuery(activeDt).removeClass('show dropdown-active');
        jQuery(activeDd).parent().siblings().toggleClass('menu-out');

        var currentPage = jQuery('#section-nav-ul .current-page');
        if (jQuery(currentPage).hasClass("menu-out")) {
            jQuery('.section-navigation-back').show();
        } else {
            jQuery('.section-navigation-back').hide();
        }
    });
    jQuery("#section-nav-ul .dropdown-toggle").on('click', function () {
        jQuery(this).parent().siblings().toggleClass('menu-out');
        jQuery(this).toggleClass('dropdown-active');
        jQuery(this).next().toggleClass('toggleExpanded');
        jQuery(this).next().find('a').first().focus();

        var currentPage = jQuery('#section-nav-ul .current-page');
        if (jQuery(currentPage).hasClass("menu-out")) {
            jQuery('.section-navigation-back').show();
        } else {
            jQuery('.section-navigation-back').hide();
        }
    });
    jQuery('#section-nav-ul .dropdown-toggle').each(function (index, value) {
        var navigationparentAttr = jQuery(this).attr('href');
        if (navigationparentAttr) {
            var navigationparentTitle = jQuery(this).html();
            var navigationparentLinks = '<li><a class="menu-parent-link" href="' + navigationparentAttr + '">' + navigationparentTitle + '</a></li>';
            jQuery(this).next().prepend(navigationparentLinks);
        }
    });

    jQuery("#section-nav-ul .dropdown-toggle").keydown(function (e) {
        if (e.keyCode == 13) {
            jQuery(this).click();
        }
    });
    // END SECTION NAVIGATION



    // Single News Related  
    jQuery('.single-news-related-content a').each(function (index, value) {
        var mobileRelated = jQuery(this).clone();
        jQuery('.mobile-related').append(mobileRelated);
    });
    // End Single News Related  


// News Landing 

    // Masonry
    var $grid = jQuery('.news-row').isotope({
        itemSelector: '.news-col'
    });

    // Change text of active filter in Dropdown
    jQuery('.button-group').each(function (i, buttonGroup) {
        var $buttonGroup = jQuery(buttonGroup);
        $buttonGroup.on('click', 'button', function (event) {
            var currentValue = jQuery(this).html();
            var $button = jQuery(event.currentTarget);
            jQuery($button).parents('.dropdown-menu').prev().html(currentValue);
            jQuery('#submitFilter').trigger('click');
        });
    });
});

// Force Masonry event to fire initially to make sure spaced correctly
jQuery(document).ready(function () {
    setTimeout(function () {
        jQuery(".news-row").masonry("layout");
    }, 500);
});







// Parallax Code - Featured Cards
function featcardParallax() {
    const featcardupElements = document.querySelectorAll('.animTop');
    const featcarddownElements = document.querySelectorAll('.animBottom');

    const featcardUp = (elements) => {
        elements.forEach((element) => {
            let y = window.innerHeight - element.getBoundingClientRect().top;
            if (y > 0) {
                element.style.transform = `translate3d(0, -${0.1 * y}px, 0)`;
            }
        });
    };

    const featcardDown = (elements) => {
        elements.forEach((element) => {
            let y = window.innerHeight - element.getBoundingClientRect().top;
            if (y > 0) {
                element.style.transform = `translate3d(0, ${0.1 * y}px, 0)`;
            }
        });
    };

    const handleScroll = () => {
        featcardUp(featcardupElements);
        featcardDown(featcarddownElements);
    };

    if (window.innerWidth > 1024) {
        featcardUp(featcardupElements);
        featcardDown(featcarddownElements);
        window.addEventListener('scroll', handleScroll);
    }
}

// END Parallax Code - Featured Cards



// Parallax Code - Full Width Image or Video
function fullwidthParralax() {
    // Get all the elements to be parallaxed
    // We have 2 as the second parallax needs to move twice as "fast" as the first in order to create the overlap
    const fullwidthbgElement = document.querySelectorAll('.fullwidthBg')
    // The parallax function - Add Negative margin to parallax div to keep content below this section close 
    const fullwidthBg = elements => {
        if ('undefined' !== elements && elements.length > 0) {
            elements.forEach(element => {
                let y = window.innerHeight - element.getBoundingClientRect().top
                if (y > 0) {
                    element.style.transform = 'translate(-' + (y / 15) + 'px ,0)'
                }
            })
        }
    }
    //If Larger Window Larger than 1024 Do Parralax
    if (jQuery(window).width() > 1024) {
        //If element is in viewport, set its position
        fullwidthBg(fullwidthbgElement)
        //Call the function on scroll
        window.onscroll = () => {
            fullwidthBg(fullwidthbgElement)
        }
    }
}

// END Parallax Code - Full Width Image or Video


/*scroll header sticky*/
jQuery(document).ready(function () {
    var lastScrollTop = 0;
    var headerHeight = 0;
    var alertHeight = 0;
    var headerHeight = jQuery('.header').height();
    var alertHeight = jQuery('.alert-section').height();

    if (alertHeight) {
        realHeaderheight = alertHeight + headerHeight;
    } else {
        realHeaderheight = headerHeight;
    }

    jQuery(window).scroll(function (event) {
        var st = jQuery(this).scrollTop();
        if (jQuery(this).scrollTop() > realHeaderheight) {
            if (jQuery(this).scrollTop() > lastScrollTop) {
                jQuery('.header').addClass('scrolling_down');
                jQuery('.header').removeClass('scrolling_up');
            } else {
                jQuery('.header').addClass('scrolling_up');
                jQuery('.header').removeClass('scrolling_down');
            }
            lastScrollTop = st;
        }
    });
});
/* END scroll header sticky*/


// Ensure Slider Elements remain in view when tabbing
jQuery(document).ready(function () {
    jQuery('.slider-section').each(function () {
        var sliderCol = jQuery(this).find('.slider-col');
        var sliderPost = jQuery(this).find('.slider');

        sliderCol.on("keydown", function (e) {
            if (e.shiftKey && e.keyCode === 9) {
                // User is tabbing backwards
                backwardsfocusSlide(sliderPost);
            } else if (e.keyCode === 9) {
                // User is tabbing forwards
                focusSlide(sliderPost);
                
                if (jQuery(this).index() !== 0) {
                    
                }
            }
        });
        

        // Slider Mobile Buttons
        const buttonRight = jQuery(this).find('.slideRight');
        const buttonLeft = jQuery(this).find('.slideLeft');
        var sliderColWidth = sliderCol.width();

        if (buttonRight.length) {
            buttonRight.on('click', function () {
                sliderPost[0].scrollLeft += sliderColWidth;
            });
        }

        if (buttonLeft.length) {
            buttonLeft.on('click', function () {
                sliderPost[0].scrollLeft -= sliderColWidth;
            });
        }
    });
});

function focusSlide(sliderPost) {
    var sliderColWidth = sliderPost.find('.slider-col').width();
    sliderPost[0].scrollLeft += sliderColWidth;
}

function backwardsfocusSlide(sliderPost) {
    var sliderColWidth = sliderPost.find('.slider-col').width();
    sliderPost[0].scrollLeft -= sliderColWidth;
}
// END Slider Elements



/*resize social -- need this to keep images as squared as possible */
function resizeSocial() {
    jQuery('.soc-container .social-item').height(jQuery('.soc-container').width() - 30);
}
jQuery(document).ready(resizeSocial);
jQuery(window).on('resize', resizeSocial);
/*end resize social */




/* CTA Button Same Height */
function adjustButtonHeights() {
    jQuery('.cta-button').height('auto'); // Reset the heights to auto first
    var heights = jQuery('.cta-button').map(function () {
        return jQuery(this).height();
    }).get();
    var maxHeight = Math.max.apply(null, heights);
    jQuery('.cta-button').height(maxHeight);
}

jQuery(document).ready(function () {
    // Call the function on page load
    adjustButtonHeights();
    // Bind the function to the window's resize event
    jQuery(window).on('resize', adjustButtonHeights);
});

/*end CTA Button */


/* Shift focus Back to video wrapper after close */
jQuery(document).ready(function () {
    var originalLink = null;

    // Store the original link when a modal is opened
    jQuery(document).on('click', '.video-wrapper', function () {
        originalLink = jQuery(this);
    });

    // Set the focus back to the original link when a modal is closed
    jQuery(document).on('hidden.bs.modal', '.modal', function () {
        if (originalLink) {
            originalLink.focus();
        }
    });

    if (jQuery('.blockquote').length) {
        jQuery('.blockquote .quote p').each(function () {
            jQuery(this).replaceWith(function () {
                return jQuery('<span>').html(jQuery(this).html());
            });
        });
    }

});


jQuery(document).ready(function () {
    setSectionNavigation();
});

/* Further Code Custimizations */

/* Section Navigation Load Onto Children if possible */
function setSectionNavigation(){
    var pathname = window.location.pathname;
    var buildPath = '';
    var builtURL = '';
    pathnameSections = pathname.split('/').filter( a => a != '');

    pathnameSections.forEach(function(thisPathnameSection){
        buildPath += '/' + thisPathnameSection;
        builtURL = window.location.origin + buildPath;
        jQuery('#section-nav-ul a[href="' + builtURL + '/"]').not('.menu-parent-link').click();
    }); 
}

/* Display search and related */
function displaySearchModal(){
    const searchModal = document.getElementById('search-modal');
    searchModal.style.display = 'block';
    const searchInput = document.getElementById('gsc-i-id1');
    searchInput.placeholder = 'How can we help you?';
    searchInput.style.background = 'none';
    document.getElementsByClassName('gsc-search-button-v2')[0].innerHTML = 'search-modal-icon.png';
    document.getElementById('gsc-i-id1').focus();
    document.body.classList.add('search-open');
  }
  
  function hideSearchModal(){
    const searchModal = document.getElementById('search-modal');
    searchModal.style.display = 'none';
    document.body.classList.remove('search-open');
  }

  document.addEventListener(
    "click",
    function(event) {      
        if (document.body.classList.contains('search-open') && !event.target.closest("#search-modal") && !event.target.closest("#search-button") && !event.target.closest(".gsq_a")) {
            hideSearchModal();
        }
    },
    false
  );

  var i = 0;
  
  function replaceBoldTags(boldTags){
    document.body.classList.add('search-has-run');
      boldTags.forEach(function(e){
          var d = document.createElement('strong');
          d.innerHTML = e.innerHTML;
        if(e.parentNode !== null){
          e.parentNode.replaceChild(d, e);
        }
      });
  }
  
  function pollTillReplaceBoldTags(i){
      i++;
      var boldTags = document.querySelectorAll('b');
      setTimeout(function(){
          if(i < 100){
              if(boldTags.length < 1 ){
                  pollTillReplaceBoldTags(i);
              }
              else{
                  replaceBoldTags(boldTags);
                  pollTillReplaceBoldTags(75);
              }
          }
      }, 50);
  }
  
  function callPollTillReplaceBoldTags(e){
      pollTillReplaceBoldTags(0);
  }
  
  function addPollTillReplaceBoldTagsToPopup(){
      searchButton = document.querySelectorAll('.gsc-search-button .gsc-search-button-v2')[0];
      searchButton.addEventListener('click', callPollTillReplaceBoldTags());
      searchForm = document.querySelectorAll('form.gsc-search-box')[0];
      searchForm.addEventListener("keyup", function onPress(e) {
          callPollTillReplaceBoldTags(e);
      });
  }
  
  window.addEventListener('load', function() {
      addPollTillReplaceBoldTagsToPopup();
  });

  /* Make main navigation dropdowns narrower if they have fewer elements */
  jQuery( document ).ready(function() {
    jQuery('#main-nav-ul > .menu-item-has-children > .dropdown-menu').each(function(){
        if (jQuery(this).children('li').length == 1) {
            jQuery(this).addClass('navbar-one-column');
        }
  
        if (jQuery(this).children('li').length == 2) {
            jQuery(this).addClass('navbar-two-columns');
        }
    });
  });
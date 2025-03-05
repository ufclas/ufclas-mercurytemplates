// Content Carousel Slider

jQuery('.content-carousel-slide').not('.slick-initialized').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    arrows: true,
    dots: true,
    autoplaySpeed: 5000,
    rtl: false, // Override rtl option
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
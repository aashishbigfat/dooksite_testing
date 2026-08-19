$(".clients-slider").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: false,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 2,
        },
        1000: {
            items: 6,
        },
    },
});

$(".destination-slider").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: [
        '<i class="fa-solid fa-arrow-left-long"></i>',
        '<i class="fa-solid fa-arrow-right-long"></i>',
    ],
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 2,
        },
        1000: {
            items: 5,
        },
    },
});
$(".destination-slider1").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: [
        '<i class="fa-solid fa-arrow-left-long"></i>',
        '<i class="fa-solid fa-arrow-right-long"></i>',
    ],
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 2,
        },
        1000: {
            items: 5,
        },
    },
});
$(".holiday_slider").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: [
        '<i class="fa-solid fa-arrow-left-long"></i>',
        '<i class="fa-solid fa-arrow-right-long"></i>',
    ],
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 2,
        },
        1000: {
            items: 6,
        },
    },
});

$('.hotel-slider').owlCarousel({
    loop: true,
    margin: 20,
    nav: true,
    dots: true,
    center: true,
    navText: [
        "<i class='fa-solid  fa-long-arrow-left'></i>",
        "<i class='fa-solid  fa-long-arrow-right'></i>"
    ],
    autoplay: true,
    autoplayTimeout: 1000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        }
    }
});

$("#visa-slider").owlCarousel({
    loop: true,
    margin: 20,
    dots: true,
    autoplay: true,
    autoplayTimeout: 7000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 2,
        },
        1000: {
            items: 5,
        },
    },
});
$(".offer-slider").owlCarousel({
    loop: true,
    margin: 20, // Reduced margin for tighter spacing
    dots: false, // Disabled navigation dots
    nav: false, // Disabled default navigation arrows
    autoplay: true,
    autoplayTimeout: 3000, // Increased autoplay timeout to 6 seconds
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1,
        },
        768: {
            items: 2,
        },
        1200: {
            items: 3,
        },
    },
});
$('.owl-carousel').owlCarousel({
  loop: true,
  margin: 10,
  nav: true,
  navText: [
    "<img src='assets/images/icons/arrow(1).png'>",
    "<img src='assets/images/icons/arrow.png'>"
  ],
  autoplay: false,
  autoplayHoverPause: true,
  responsive: {
    0: {
      items: 1
    },
    600: {
      items: 3
    },
    1000: {
      items: 4
    }
  }
})
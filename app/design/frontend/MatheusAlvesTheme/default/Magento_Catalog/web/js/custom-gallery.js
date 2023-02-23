require([
  'jquery',
  'domReady!',
  'slick'
], function($, domReady) {
  $(document).ready(function($) {
    const slickConfig = {
      dots: true,
      infinite: true,
      speed: 300,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    };

    if ($(window).width() < 769) {
      $(".custom-product-gallery").slick(slickConfig);
    }
  });
});

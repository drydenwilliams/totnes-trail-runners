jQuery(document).ready(function($) {


  /*
   * Utility to scroll to an anchor smoothly
   * 
   */
  function scrollToAnchor(id){
    $('html,body').animate({
      scrollTop: $('#' + id).offset().top - 50
    }, 'slow');
  }

  /*
   * For firing GA events
   * 
   */
  $('.ga-event').click(function () {
    const eventCategory = $(this).data('category');
    const eventAction = $(this).data('action');
    const eventLabel = $(this).data('label');
    const redirectUrl = $(this).data('redirect');
    const openTab = $(this).data('tab');

    gtag('event', eventAction, {
      event_category: eventCategory,
      event_label: eventLabel
    });

    // Hacky re-direct
    if (openTab) {
      if (openTab === 'open') {
        window.open(redirectUrl, '_blank');
      } else {
        document.location.href = redirectUrl;
      }
    }
    
  });


//   $('.hero-slider').slick({
//     autoplay: true,
//     arrows: false, //Set these to whatever you need
// });

  $('.hero-slider').slick({
    arrows: true,
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });


});
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

  /*
   * For filters checkboxes because we can't easily change the markup
   * 
   */
  $('.cat-item label').click(function (evt) {
    evt.preventDefault();
    console.log($(this).hasClass('active'));
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
      $(this).children().prop('checked', false);
    } else {
      $(this).addClass('active');
      $(this).children().prop('checked', true);
    }
  });

  /*
   * For toggling the filters open/close
   * 
   */
  $('.filters-btn').click(function () {
    if ($('.filters-btn-wrapper--active').hasClass('filters-btn-wrapper--active')) {
      $('.filters-btn-wrapper').removeClass('filters-btn-wrapper--active');
    } else {
      $('.filters-btn-wrapper').addClass('filters-btn-wrapper--active');
    }
    $('.filters').slideToggle();
  });

  /*
   * Opneing the modal
   * 
   */
  $('#loginModal').on('shown.bs.modal', function () {
    $('[data="modal"]').trigger('focus')
  })

  /*
   * Once the user has registered we need to open the login modal so they can login
   * 
   */
  function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

  const userRegistered = getParameterByName('registered');
  if (userRegistered) {
    $('#loginModal').modal('show');
  }

});
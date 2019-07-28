$('ul.navbar-nav li.dropdown').hover(function() {
  $(this).find('.nav-item').stop(true, true).delay(200).fadeIn(500);
}, function() {
  $(this).find('.nav-item').stop(true, true).delay(200).fadeOut(500);
});

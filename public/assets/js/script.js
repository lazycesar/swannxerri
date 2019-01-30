$(document).ready(function() {
  $(".header__icon-bar").click(function(e) {
    $(".header__menu").toggleClass("is-open");
    e.preventDefault();
  });
});

ScrollReveal().reveal(".formation__left__image");

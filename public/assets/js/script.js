$(document).ready(function() {
  $(".header__icon-bar").click(function(e) {
    $(".header__menu").toggleClass("is-open");
    e.preventDefault();
  });
});

var slideRight = {
  distance: "100px",
  origin: "right",
  opacity: 0,
  interval: 1000
};
var slideLeft = {
  distance: "100px",
  origin: "left",
  opacity: 0,
  interval: 1000
};

ScrollReveal().reveal(".formation__left__image", slideRight);
ScrollReveal().reveal(".about__left__image", slideLeft);
ScrollReveal().reveal(".slideLeft", slideLeft);
ScrollReveal().reveal(".slideRight", slideRight);
ScrollReveal().reveal(".card-feedback", { interval: 600 });

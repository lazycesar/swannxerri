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

// Get the container element
var btnContainer = document.getElementById("HomeNav");

// Get all buttons with class="btn" inside the container
var btns = btnContainer.getElementsByClassName("header__menu__item");

// Loop through the buttons and add the active class to the current/clicked button
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}

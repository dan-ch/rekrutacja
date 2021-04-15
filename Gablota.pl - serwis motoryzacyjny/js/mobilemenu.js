const burger = document.querySelector('.header__burger');
const mobile = document.querySelector('.header__mobile');

burger.addEventListener('click', function () {
  mobile.classList.toggle('mobile-active');
})
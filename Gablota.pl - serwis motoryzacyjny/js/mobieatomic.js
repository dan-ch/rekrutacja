const img = document.querySelector('.search__confirm img');
const atomic = document.querySelector('.search__atomic');

img.addEventListener('click', function () {
  atomic.classList.toggle('grid-active');
  img.classList.toggle('arrow-rot');
});
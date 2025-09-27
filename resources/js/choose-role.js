// Import background images
import bg1 from '../images/bg1.jpg';
import bg2 from '../images/bg2.jpg';
import bg3 from '../images/bg3.jpg';
import bg4 from '../images/bg4.jpg';

// Import logo images and make them globally available
import adminLogo from '../images/admin-logo.png';
import photographerLogo from '../images/photographer-logo.png';
import clientLogo from '../images/client-logo.png';

// Make logos globally available for the blade template
window.adminLogo = adminLogo;
window.photographerLogo = photographerLogo;
window.clientLogo = clientLogo;

const backgrounds = [bg1, bg2, bg3, bg4];

let current = 0;
const sliderContainer = document.getElementById('slider-container');

function createSlide(src) {
  const slide = document.createElement('div');
  slide.classList.add('slide', 'hidden-slide');
  slide.style.backgroundImage = `url(${src})`;
  slide.style.backgroundSize = 'cover';
  slide.style.backgroundPosition = 'center';
  slide.style.position = 'absolute';
  slide.style.inset = 0;
  slide.style.transition = 'transform 1s ease-in-out';
  return slide;
}

let currentSlide = createSlide(backgrounds[current]);
currentSlide.classList.add('active-slide');
sliderContainer.appendChild(currentSlide);

function swipeToNext() {
  const next = (current + 1) % backgrounds.length;
  const nextSlide = createSlide(backgrounds[next]);
  sliderContainer.appendChild(nextSlide);

  setTimeout(() => {
    nextSlide.classList.remove('hidden-slide');
    nextSlide.classList.add('active-slide');
    currentSlide.classList.remove('active-slide');
    currentSlide.classList.add('exit-slide');
  }, 50);

  setTimeout(() => {
    sliderContainer.removeChild(currentSlide);
    currentSlide = nextSlide;
    current = next;
  }, 1000);
}

setInterval(swipeToNext, 2000);

window.navigateTo = function (page) {
  document.body.style.transition = "opacity 0.3s ease";
  document.body.style.opacity = "0.5";
  setTimeout(() => {
    window.location.href = page;
  }, 300);
};
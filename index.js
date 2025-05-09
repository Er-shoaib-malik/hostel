const slides = document.querySelectorAll(".slide");

let counter = 0;

slides.forEach((slide, index) => {
    slide.style.left = `${index * 100}%`;
});

function slideCarousel() {
    counter++;
    slides.forEach((slide) => {
        slide.style.transform = `translateX(-${counter * 100}%)`;
    });

    if (counter >= slides.length - 1) {
        counter = -1; 
    }
}

setInterval(slideCarousel, 2500);

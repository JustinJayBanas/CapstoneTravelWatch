// Example of smooth scrolling for navigation links (if you plan to implement anchor links)
document.querySelectorAll('.navbar a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
// Adding hover effect for fun fact cards
document.querySelectorAll('.fact-card').forEach(card => {
    card.addEventListener('mouseover', function() {
        card.style.transform = 'scale(1.1)';
        card.style.transition = 'transform 0.2s ease-in-out';
    });
    
    card.addEventListener('mouseout', function() {
        card.style.transform = 'scale(1)';
    });
});
let currentSlide = 0;
const aboutCards = document.querySelectorAll('.about-card');

function showSlide(slideIndex) {
    aboutCards.forEach((card, index) => {
        card.style.display = (index === slideIndex) ? 'block' : 'none';
    });
}

document.querySelector('.next').addEventListener('click', function() {
    currentSlide = (currentSlide + 1) % aboutCards.length;
    showSlide(currentSlide);
});

document.querySelector('.prev').addEventListener('click', function() {
    currentSlide = (currentSlide - 1 + aboutCards.length) % aboutCards.length;
    showSlide(currentSlide);
});

showSlide(currentSlide);
document.querySelectorAll('.fact-card').forEach(card => {
    card.addEventListener('click', function() {
        alert('More information about ' + this.textContent.trim());
    });
});
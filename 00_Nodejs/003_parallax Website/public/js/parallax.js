// Simple parallax enhancement
document.addEventListener('DOMContentLoaded', function() {
    // Add scroll event listener for additional parallax effects
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.parallax');
        
        parallaxElements.forEach(function(element) {
            const rate = scrolled * -0.5;
            element.style.transform = `translate3d(0px, ${rate}px, 0px)`;
        });
    });

    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    console.log('Parallax website loaded successfully!');
});
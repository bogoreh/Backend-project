document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to buttons
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                button.disabled = true;
                
                // Re-enable button after 5 seconds (fallback)
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 5000);
            }
        });
    });

    // Auto-refresh currency data every 5 minutes
    setInterval(() => {
        const currencyForm = document.querySelector('form[action*="get_currency"]');
        if (currencyForm && !document.querySelector('form:has(button[disabled])')) {
            currencyForm.querySelector('button[type="submit"]').click();
        }
    }, 300000);

    // Add smooth scrolling to results
    const smoothScrollToResults = () => {
        const resultsSection = document.querySelector('.results-section');
        if (resultsSection) {
            resultsSection.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    };

    // Scroll to results when API data is loaded
    if (document.querySelector('.results-section')) {
        setTimeout(smoothScrollToResults, 500);
    }
});
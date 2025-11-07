document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const urlInput = document.getElementById('url');
    
    form.addEventListener('submit', function(e) {
        const url = urlInput.value.trim();
        
        if (!url) {
            e.preventDefault();
            alert('Please enter a URL');
            return;
        }
        
        // Basic URL format validation
        if (!url.startsWith('http://') && !url.startsWith('https://')) {
            e.preventDefault();
            alert('Please enter a valid URL starting with http:// or https://');
            return;
        }
    });
    
    // Add some interactivity
    urlInput.addEventListener('focus', function() {
        this.style.borderColor = '#3498db';
    });
    
    urlInput.addEventListener('blur', function() {
        this.style.borderColor = '#ddd';
    });
});
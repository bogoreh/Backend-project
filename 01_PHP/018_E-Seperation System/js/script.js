// JavaScript for dark theme enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Auto-set separation date to today if empty
    const separationDate = document.getElementById('separation_date');
    if (separationDate && !separationDate.value) {
        const today = new Date().toISOString().split('T')[0];
        separationDate.value = today;
    }

    // Add confirmation for status changes
    const statusSelects = document.querySelectorAll('select[name="status"]');
    statusSelects.forEach(select => {
        select.addEventListener('change', function(e) {
            const employeeName = this.closest('tr').querySelector('.fw-semibold').textContent;
            if (!confirm(`Are you sure you want to change the status for ${employeeName}?`)) {
                this.form.reset();
                e.preventDefault();
            }
        });
    });

    // Enhanced form validation with dark theme
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                
                // Add dark theme styling to invalid fields
                const invalidFields = form.querySelectorAll(':invalid');
                invalidFields.forEach(field => {
                    field.classList.add('is-invalid');
                    // Add custom dark theme styling
                    field.style.borderColor = '#dc3545';
                    field.style.backgroundImage = `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7V7'/%3e%3c/svg%3e")`;
                });
            }
            
            form.classList.add('was-validated');
        }, false);
    });

    // Real-time form validation
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                this.style.borderColor = '#198754';
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                this.style.borderColor = '#dc3545';
            }
        });
    });

    // Add loading states to buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="loading me-2"></span> Processing...';
            this.disabled = true;
            
            // Revert after 3 seconds if still on page
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 3000);
        });
    });

    // Add hover effects to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.style.transition = 'all 0.3s ease';
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});

// Utility function to format dates
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}
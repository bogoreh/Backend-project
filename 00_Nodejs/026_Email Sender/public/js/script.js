document.addEventListener('DOMContentLoaded', function() {
  const emailForm = document.getElementById('emailForm');
  const alertDiv = document.getElementById('alert');
  const submitBtn = document.getElementById('submitBtn');
  const originalBtnText = submitBtn.innerHTML;

  emailForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(emailForm);
    const emailData = {
      to: formData.get('to'),
      subject: formData.get('subject'),
      message: formData.get('message')
    };

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="loading"></div>Sending...';

    // Hide any previous alerts
    hideAlert();

    try {
      const response = await fetch('/send-email', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(emailData)
      });

      const result = await response.json();

      if (result.success) {
        showAlert('Email sent successfully!', 'success');
        emailForm.reset();
      } else {
        showAlert(result.error || 'Failed to send email', 'error');
      }
    } catch (error) {
      console.error('Error:', error);
      showAlert('Network error. Please try again.', 'error');
    } finally {
      // Reset button state
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
    }
  });

  function showAlert(message, type) {
    alertDiv.textContent = message;
    alertDiv.className = `alert ${type}`;
    alertDiv.style.display = 'block';
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
      setTimeout(hideAlert, 5000);
    }
  }

  function hideAlert() {
    alertDiv.style.display = 'none';
    alertDiv.className = 'alert';
  }

  // Add input validation
  const inputs = emailForm.querySelectorAll('input, textarea');
  inputs.forEach(input => {
    input.addEventListener('input', function() {
      this.style.borderColor = '#e1e5e9';
    });
  });
});
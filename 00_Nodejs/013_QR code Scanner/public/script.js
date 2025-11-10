document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    const urlForm = document.getElementById('urlForm');
    const qrImageInput = document.getElementById('qrImage');
    const fileName = document.querySelector('.file-name');
    const resultSection = document.getElementById('result');
    const errorSection = document.getElementById('error');
    const loadingSection = document.getElementById('loading');
    const resultData = document.getElementById('resultData');
    const errorMessage = document.getElementById('errorMessage');
    const copyButton = document.getElementById('copyButton');
    const newScanButton = document.getElementById('newScanButton');

    // Update file name display
    qrImageInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = 'No file chosen';
        }
    });

    // Handle file upload form
    uploadForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const file = qrImageInput.files[0];
        if (!file) {
            showError('Please select an image file');
            return;
        }

        await scanQRCode(file);
    });

    // Handle URL form
    urlForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const imageUrl = document.getElementById('imageUrl').value;
        if (!imageUrl) {
            showError('Please enter an image URL');
            return;
        }

        await scanQRCodeFromUrl(imageUrl);
    });

    // Scan QR code from file
    async function scanQRCode(file) {
        hideResults();
        showLoading();
        
        const formData = new FormData();
        formData.append('qrcode', file);

        try {
            const response = await fetch('/api/qr/scan', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                showResult(data.data);
            } else {
                showError(data.message || 'Failed to scan QR code');
            }
        } catch (error) {
            showError('Network error: ' + error.message);
        } finally {
            hideLoading();
        }
    }

    // Scan QR code from URL
    async function scanQRCodeFromUrl(imageUrl) {
        hideResults();
        showLoading();

        try {
            const response = await fetch('/api/qr/scan-url', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ imageUrl })
            });

            const data = await response.json();
            
            if (data.success) {
                showResult(data.data);
            } else {
                showError(data.message || 'Failed to scan QR code from URL');
            }
        } catch (error) {
            showError('Network error: ' + error.message);
        } finally {
            hideLoading();
        }
    }

    // Show result
    function showResult(data) {
        resultData.textContent = data;
        resultSection.style.display = 'block';
        errorSection.style.display = 'none';
        
        // Scroll to result
        resultSection.scrollIntoView({ behavior: 'smooth' });
    }

    // Show error
    function showError(message) {
        errorMessage.textContent = message;
        errorSection.style.display = 'block';
        resultSection.style.display = 'none';
        
        // Scroll to error
        errorSection.scrollIntoView({ behavior: 'smooth' });
    }

    // Hide all results
    function hideResults() {
        resultSection.style.display = 'none';
        errorSection.style.display = 'none';
    }

    // Show loading
    function showLoading() {
        loadingSection.style.display = 'block';
    }

    // Hide loading
    function hideLoading() {
        loadingSection.style.display = 'none';
    }

    // Copy to clipboard
    copyButton.addEventListener('click', function() {
        const text = resultData.textContent;
        navigator.clipboard.writeText(text).then(() => {
            const originalText = copyButton.textContent;
            copyButton.textContent = 'Copied!';
            copyButton.style.background = '#28a745';
            
            setTimeout(() => {
                copyButton.textContent = originalText;
                copyButton.style.background = '#17a2b8';
            }, 2000);
        }).catch(err => {
            showError('Failed to copy to clipboard: ' + err);
        });
    });

    // New scan
    newScanButton.addEventListener('click', function() {
        hideResults();
        uploadForm.reset();
        urlForm.reset();
        fileName.textContent = 'No file chosen';
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
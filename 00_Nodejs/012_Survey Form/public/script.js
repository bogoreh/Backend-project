document.getElementById('surveyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const surveyData = {
        name: formData.get('name'),
        email: formData.get('email'),
        age: parseInt(formData.get('age')),
        satisfaction: formData.get('satisfaction'),
        category: formData.get('category'),
        source: formData.getAll('source'),
        comments: formData.get('comments'),
        recommend: formData.get('recommend')
    };

    // Validate checkboxes (convert to array)
    if (surveyData.source.length === 0) {
        surveyData.source = ['None selected'];
    }

    submitSurvey(surveyData);
});

function submitSurvey(surveyData) {
    const messageDiv = document.getElementById('message');
    
    fetch('/submit-survey', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(surveyData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.textContent = data.message;
            messageDiv.className = 'message success';
            document.getElementById('surveyForm').reset();
            
            // Clear message after 5 seconds
            setTimeout(() => {
                messageDiv.textContent = '';
                messageDiv.className = 'message';
            }, 5000);
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        messageDiv.textContent = 'Error submitting survey: ' + error.message;
        messageDiv.className = 'message error';
    });
}
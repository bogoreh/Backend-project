class MemeGenerator {
    constructor() {
        this.templates = [];
        this.init();
    }

    async init() {
        await this.loadTemplates();
        this.setupEventListeners();
    }

    async loadTemplates() {
        try {
            const response = await fetch('/api/memes/templates');
            this.templates = await response.json();
            this.populateTemplateSelect();
        } catch (error) {
            console.error('Error loading templates:', error);
        }
    }

    populateTemplateSelect() {
        const select = document.getElementById('templateSelect');
        select.innerHTML = '<option value="">Select a template...</option>';
        
        this.templates.forEach(template => {
            const option = document.createElement('option');
            option.value = template.id;
            option.textContent = template.name;
            select.appendChild(option);
        });
    }

    setupEventListeners() {
        document.getElementById('generateBtn').addEventListener('click', () => {
            this.generateMeme();
        });

        document.getElementById('downloadBtn').addEventListener('click', () => {
            this.downloadMeme();
        });
    }

    async generateMeme() {
        const templateId = document.getElementById('templateSelect').value;
        const topText = document.getElementById('topText').value;
        const bottomText = document.getElementById('bottomText').value;

        if (!templateId) {
            alert('Please select a template!');
            return;
        }

        // Show loading
        document.getElementById('loading').style.display = 'block';
        document.getElementById('memeResult').style.display = 'none';

        try {
            const response = await fetch('/api/memes/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    templateId: parseInt(templateId),
                    topText: topText,
                    bottomText: bottomText
                })
            });

            if (!response.ok) {
                throw new Error('Failed to generate meme');
            }

            const blob = await response.blob();
            const imageUrl = URL.createObjectURL(blob);
            
            // Display the generated meme
            document.getElementById('memeImage').src = imageUrl;
            document.getElementById('loading').style.display = 'none';
            document.getElementById('memeResult').style.display = 'block';

            // Store the blob for download
            this.currentMemeBlob = blob;

        } catch (error) {
            console.error('Error:', error);
            alert('Error generating meme. Please try again.');
            document.getElementById('loading').style.display = 'none';
        }
    }

    downloadMeme() {
        if (!this.currentMemeBlob) return;

        const url = URL.createObjectURL(this.currentMemeBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `meme-${Date.now()}.png`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
}

// Initialize the meme generator when the page loads
document.addEventListener('DOMContentLoaded', () => {
    new MemeGenerator();
});
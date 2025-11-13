class VideoStreamingApp {
    constructor() {
        this.videoPlayer = document.getElementById('videoPlayer');
        this.videoInfo = document.getElementById('videoInfo');
        this.videoList = document.getElementById('videoList');
        this.uploadForm = document.getElementById('uploadForm');
        this.uploadMessage = document.getElementById('uploadMessage');
        
        this.init();
    }
    
    init() {
        this.loadVideos();
        this.setupEventListeners();
    }
    
    setupEventListeners() {
        this.uploadForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.uploadVideo();
        });
    }
    
    async loadVideos() {
        try {
            const response = await fetch('/api/videos');
            const videos = await response.json();
            this.displayVideos(videos);
        } catch (error) {
            console.error('Error loading videos:', error);
        }
    }
    
    displayVideos(videos) {
        if (videos.length === 0) {
            this.videoList.innerHTML = '<p>No videos available. Upload some videos to get started!</p>';
            return;
        }
        
        this.videoList.innerHTML = videos.map(video => `
            <div class="video-item">
                <h3>${this.formatVideoName(video.name)}</h3>
                <p>Uploaded: ${new Date(video.uploadDate).toLocaleDateString()}</p>
                <div class="video-actions">
                    <button class="play" onclick="app.playVideo('${video.name}', '${video.path}')">
                        ▶ Play
                    </button>
                    <button class="delete" onclick="app.deleteVideo('${video.name}')">
                        🗑 Delete
                    </button>
                </div>
            </div>
        `).join('');
    }
    
    formatVideoName(filename) {
        // Remove extension and replace dashes/underscores with spaces
        return filename
            .replace(/\.[^/.]+$/, '')
            .replace(/[-_]/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase());
    }
    
    playVideo(filename, path) {
        this.videoPlayer.src = `/video/${filename}`;
        this.videoInfo.innerHTML = `
            <h3>Now Playing: ${this.formatVideoName(filename)}</h3>
            <p>Click play to start streaming</p>
        `;
        
        // Auto play when video is loaded
        this.videoPlayer.onloadeddata = () => {
            this.videoPlayer.play().catch(e => {
                console.log('Autoplay prevented:', e);
            });
        };
    }
    
    async uploadVideo() {
        const formData = new FormData(this.uploadForm);
        
        try {
            const response = await fetch('/api/upload', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (response.ok) {
                this.showMessage('Video uploaded successfully!', 'success');
                this.uploadForm.reset();
                this.loadVideos();
            } else {
                this.showMessage(result.error || 'Upload failed', 'error');
            }
        } catch (error) {
            this.showMessage('Upload failed: ' + error.message, 'error');
        }
    }
    
    async deleteVideo(filename) {
        if (!confirm('Are you sure you want to delete this video?')) {
            return;
        }
        
        try {
            const response = await fetch(`/api/video/${filename}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (response.ok) {
                this.showMessage('Video deleted successfully!', 'success');
                this.loadVideos();
                
                // If deleted video was playing, clear player
                if (this.videoPlayer.src.includes(filename)) {
                    this.videoPlayer.src = '';
                    this.videoInfo.innerHTML = '';
                }
            } else {
                this.showMessage(result.error || 'Delete failed', 'error');
            }
        } catch (error) {
            this.showMessage('Delete failed: ' + error.message, 'error');
        }
    }
    
    showMessage(message, type) {
        this.uploadMessage.textContent = message;
        this.uploadMessage.className = `message ${type}`;
        this.uploadMessage.style.display = 'block';
        
        setTimeout(() => {
            this.uploadMessage.style.display = 'none';
        }, 5000);
    }
}

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.app = new VideoStreamingApp();
});
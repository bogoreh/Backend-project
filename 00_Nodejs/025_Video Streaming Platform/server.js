const express = require('express');
const fs = require('fs');
const path = require('path');
const multer = require('multer');

const app = express();
const PORT = process.env.PORT || 3000;

// Create necessary directories
const directories = ['uploads', 'videos', 'public'];
directories.forEach(dir => {
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
    console.log(`Created directory: ${dir}`);
  }
});

// Middleware
app.use(express.static('public'));
app.use(express.json());

// Configure multer for file uploads
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, 'uploads/');
  },
  filename: (req, file, cb) => {
    // Clean filename and add timestamp
    const cleanName = file.originalname.replace(/[^a-zA-Z0-9.-]/g, '_');
    cb(null, Date.now() + '-' + cleanName);
  }
});

const upload = multer({
  storage: storage,
  fileFilter: (req, file, cb) => {
    if (file.mimetype.startsWith('video/')) {
      cb(null, true);
    } else {
      cb(new Error('Only video files are allowed!'), false);
    }
  },
  limits: {
    fileSize: 100 * 1024 * 1024 // 100MB limit
  }
});

// Routes
app.get('/', (req, res) => {
  const htmlPath = path.join(__dirname, 'public', 'index.html');
  if (fs.existsSync(htmlPath)) {
    res.sendFile(htmlPath);
  } else {
    res.send(`
      <html>
        <body>
          <h1>Video Streaming Platform</h1>
          <p>Please make sure the frontend files are in the public directory.</p>
          <p>Server is running correctly!</p>
        </body>
      </html>
    `);
  }
});

// Get list of videos
app.get('/api/videos', (req, res) => {
  try {
    const videos = [];
    
    // Check both directories for videos
    ['videos', 'uploads'].forEach(dir => {
      const dirPath = path.join(__dirname, dir);
      if (fs.existsSync(dirPath)) {
        const files = fs.readdirSync(dirPath);
        files.forEach(file => {
          if (file.match(/\.(mp4|avi|mov|wmv|flv|webm|mkv)$/i)) {
            const filePath = path.join(dirPath, file);
            const stats = fs.statSync(filePath);
            videos.push({
              name: file,
              path: `/${dir}/${file}`,
              uploadDate: stats.birthtime,
              size: stats.size
            });
          }
        });
      }
    });
    
    res.json(videos);
  } catch (error) {
    console.error('Error reading videos:', error);
    res.status(500).json({ error: 'Failed to load videos' });
  }
});

// Stream video
app.get('/video/:filename', (req, res) => {
  const filename = req.params.filename;
  
  // Security: Prevent directory traversal
  if (filename.includes('..') || filename.includes('/') || filename.includes('\\')) {
    return res.status(400).json({ error: 'Invalid filename' });
  }
  
  let videoPath;
  
  // Check in both directories
  if (fs.existsSync(path.join(__dirname, 'videos', filename))) {
    videoPath = path.join(__dirname, 'videos', filename);
  } else if (fs.existsSync(path.join(__dirname, 'uploads', filename))) {
    videoPath = path.join(__dirname, 'uploads', filename);
  } else {
    return res.status(404).json({ error: 'Video not found' });
  }
  
  try {
    const stat = fs.statSync(videoPath);
    const fileSize = stat.size;
    const range = req.headers.range;
    
    if (range) {
      // Handle range requests for video seeking
      const parts = range.replace(/bytes=/, "").split("-");
      const start = parseInt(parts[0], 10);
      const end = parts[1] ? parseInt(parts[1], 10) : fileSize - 1;
      const chunksize = (end - start) + 1;
      
      const head = {
        'Content-Range': `bytes ${start}-${end}/${fileSize}`,
        'Accept-Ranges': 'bytes',
        'Content-Length': chunksize,
        'Content-Type': 'video/mp4',
      };
      
      res.writeHead(206, head);
      fs.createReadStream(videoPath, { start, end }).pipe(res);
    } else {
      // Send entire video
      const head = {
        'Content-Length': fileSize,
        'Content-Type': 'video/mp4',
      };
      res.writeHead(200, head);
      fs.createReadStream(videoPath).pipe(res);
    }
  } catch (error) {
    console.error('Error streaming video:', error);
    res.status(500).json({ error: 'Error streaming video' });
  }
});

// Upload video
app.post('/api/upload', upload.single('video'), (req, res) => {
  try {
    if (!req.file) {
      return res.status(400).json({ error: 'No file uploaded' });
    }
    
    res.json({
      message: 'File uploaded successfully',
      filename: req.file.filename,
      path: `/uploads/${req.file.filename}`
    });
  } catch (error) {
    console.error('Upload error:', error);
    res.status(500).json({ error: 'Upload failed' });
  }
});

// Delete video
app.delete('/api/video/:filename', (req, res) => {
  const filename = req.params.filename;
  
  // Security: Prevent directory traversal
  if (filename.includes('..') || filename.includes('/') || filename.includes('\\')) {
    return res.status(400).json({ error: 'Invalid filename' });
  }
  
  try {
    let deleted = false;
    
    // Try to delete from both directories
    ['videos', 'uploads'].forEach(dir => {
      const filePath = path.join(__dirname, dir, filename);
      if (fs.existsSync(filePath)) {
        fs.unlinkSync(filePath);
        deleted = true;
        console.log(`Deleted video: ${filePath}`);
      }
    });
    
    if (deleted) {
      res.json({ message: 'Video deleted successfully' });
    } else {
      res.status(404).json({ error: 'Video not found' });
    }
  } catch (error) {
    console.error('Delete error:', error);
    res.status(500).json({ error: 'Failed to delete video' });
  }
});

// Error handling
app.use((error, req, res, next) => {
  if (error instanceof multer.MulterError) {
    if (error.code === 'LIMIT_FILE_SIZE') {
      return res.status(400).json({ error: 'File too large (max 100MB)' });
    }
  }
  console.error('Server error:', error);
  res.status(500).json({ error: error.message });
});

// 404 handler
app.use((req, res) => {
  res.status(404).json({ error: 'Route not found' });
});

// Start server
app.listen(PORT, () => {
  console.log(`🎬 Video Streaming Platform running on http://localhost:${PORT}`);
  console.log(`📁 Uploads directory: ${path.join(__dirname, 'uploads')}`);
  console.log(`📁 Videos directory: ${path.join(__dirname, 'videos')}`);
});
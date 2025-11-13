const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const path = require('path');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

// Serve static files
app.use(express.static(path.join(__dirname, 'public')));

// Store connected users
const users = new Map();

// Socket.io connection handling
io.on('connection', (socket) => {
    console.log('New user connected:', socket.id);

    // Handle user joining
    socket.on('user-join', (username) => {
        users.set(socket.id, username);
        socket.broadcast.emit('user-joined', username);
        io.emit('update-users', Array.from(users.values()));
    });

    // Handle chat messages
    socket.on('send-message', (data) => {
        const username = users.get(socket.id);
        io.emit('receive-message', {
            username: username,
            message: data.message,
            timestamp: new Date().toLocaleTimeString()
        });
    });

    // Handle typing indicator
    socket.on('typing', (data) => {
        const username = users.get(socket.id);
        socket.broadcast.emit('user-typing', {
            username: username,
            isTyping: data.isTyping
        });
    });

    // Handle disconnect
    socket.on('disconnect', () => {
        const username = users.get(socket.id);
        if (username) {
            users.delete(socket.id);
            socket.broadcast.emit('user-left', username);
            io.emit('update-users', Array.from(users.values()));
        }
        console.log('User disconnected:', socket.id);
    });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
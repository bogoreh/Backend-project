const socket = io();
let currentUsername = '';

// DOM Elements
const usernameModal = document.getElementById('username-modal');
const usernameInput = document.getElementById('username-input');
const joinButton = document.getElementById('join-button');
const messageInput = document.getElementById('message-input');
const sendButton = document.getElementById('send-button');
const messagesContainer = document.getElementById('messages-container');
const usersList = document.getElementById('users-list');
const onlineCount = document.getElementById('online-count');
const typingIndicator = document.getElementById('typing-indicator');

let typingTimer;

// Join chat
joinButton.addEventListener('click', joinChat);
usernameInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') joinChat();
});

function joinChat() {
    const username = usernameInput.value.trim();
    if (username) {
        currentUsername = username;
        socket.emit('user-join', username);
        usernameModal.style.display = 'none';
        messageInput.focus();
    }
}

// Send message
sendButton.addEventListener('click', sendMessage);
messageInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

function sendMessage() {
    const message = messageInput.value.trim();
    if (message) {
        socket.emit('send-message', { message });
        messageInput.value = '';
        socket.emit('typing', { isTyping: false });
    }
}

// Typing indicator
messageInput.addEventListener('input', () => {
    socket.emit('typing', { isTyping: true });
    
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
        socket.emit('typing', { isTyping: false });
    }, 1000);
});

// Socket event listeners
socket.on('user-joined', (username) => {
    addSystemMessage(`${username} joined the chat`);
});

socket.on('user-left', (username) => {
    addSystemMessage(`${username} left the chat`);
});

socket.on('receive-message', (data) => {
    addMessage(data.username, data.message, data.timestamp, data.username !== currentUsername);
});

socket.on('update-users', (users) => {
    updateUsersList(users);
    onlineCount.textContent = users.length;
});

socket.on('user-typing', (data) => {
    if (data.isTyping) {
        typingIndicator.textContent = `${data.username} is typing...`;
    } else {
        typingIndicator.textContent = '';
    }
});

// Helper functions
function addMessage(username, message, timestamp, isOther = true) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isOther ? 'other' : 'own'}`;
    
    messageDiv.innerHTML = `
        <div class="message-header">${isOther ? username : 'You'}</div>
        <div class="message-content">${message}</div>
        <div class="message-time">${timestamp}</div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function addSystemMessage(message) {
    const systemDiv = document.createElement('div');
    systemDiv.className = 'system-message';
    systemDiv.textContent = message;
    messagesContainer.appendChild(systemDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function updateUsersList(users) {
    usersList.innerHTML = '';
    users.forEach(user => {
        const li = document.createElement('li');
        li.textContent = user;
        usersList.appendChild(li);
    });
}

// Handle page refresh/close
window.addEventListener('beforeunload', () => {
    socket.disconnect();
});
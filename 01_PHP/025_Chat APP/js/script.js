class ChatApp {
    constructor() {
        this.messagesContainer = document.getElementById('messages');
        this.onlineUsersList = document.getElementById('online-users-list');
        this.messageForm = document.getElementById('message-form');
        this.messageInput = document.getElementById('message');
        
        this.init();
    }
    
    init() {
        this.loadMessages();
        this.setupEventListeners();
        this.startAutoRefresh();
    }
    
    setupEventListeners() {
        this.messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });
        
        // Auto-resize message input
        this.messageInput.addEventListener('input', () => {
            this.messageInput.style.height = 'auto';
            this.messageInput.style.height = Math.min(this.messageInput.scrollHeight, 120) + 'px';
        });
    }
    
    async sendMessage() {
        const message = this.messageInput.value.trim();
        
        if (!message) return;
        
        try {
            const formData = new FormData();
            formData.append('message', message);
            
            const response = await fetch('process_message.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.messageInput.value = '';
                this.messageInput.style.height = 'auto';
                this.loadMessages();
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    }
    
    async loadMessages() {
        try {
            const response = await fetch('get_messages.php');
            const data = await response.json();
            
            this.displayMessages(data.messages);
            this.displayOnlineUsers(data.onlineUsers);
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }
    
    displayMessages(messages) {
        this.messagesContainer.innerHTML = '';
        
        messages.forEach(message => {
            const messageElement = this.createMessageElement(message);
            this.messagesContainer.appendChild(messageElement);
        });
        
        this.scrollToBottom();
    }
    
    createMessageElement(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${message.user_id == userId ? 'own' : 'other'}`;
        
        const time = new Date(message.created_at).toLocaleTimeString([], { 
            hour: '2-digit', minute: '2-digit' 
        });
        
        messageDiv.innerHTML = `
            <div class="message-header">${message.username}</div>
            <div class="message-text">${this.escapeHtml(message.message)}</div>
            <div class="message-time">${time}</div>
        `;
        
        return messageDiv;
    }
    
    displayOnlineUsers(users) {
        this.onlineUsersList.innerHTML = '';
        
        users.forEach(username => {
            const userElement = document.createElement('div');
            userElement.className = 'online-user';
            userElement.innerHTML = `
                <div class="online-indicator"></div>
                <span>${this.escapeHtml(username)}</span>
            `;
            this.onlineUsersList.appendChild(userElement);
        });
    }
    
    scrollToBottom() {
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    }
    
    startAutoRefresh() {
        // Refresh messages every 2 seconds
        setInterval(() => {
            this.loadMessages();
        }, 2000);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chat app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (typeof userId !== 'undefined') {
        new ChatApp();
    }
});
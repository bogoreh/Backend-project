let todos = [];
let currentFilter = 'all';

// Load todos when page loads
document.addEventListener('DOMContentLoaded', loadTodos);

async function loadTodos() {
    try {
        const response = await fetch('/api/todos');
        todos = await response.json();
        renderTodos();
    } catch (error) {
        console.error('Error loading todos:', error);
    }
}

function renderTodos() {
    const todoList = document.getElementById('todoList');
    const filteredTodos = getFilteredTodos();
    
    if (filteredTodos.length === 0) {
        todoList.innerHTML = `
            <div class="empty-state">
                <h3>No todos found</h3>
                <p>${currentFilter === 'all' ? 'Add your first todo above!' : `No ${currentFilter} todos`}</p>
            </div>
        `;
        return;
    }

    todoList.innerHTML = filteredTodos.map(todo => `
        <div class="todo-item ${todo.completed ? 'completed' : ''}">
            <input 
                type="checkbox" 
                class="todo-checkbox" 
                ${todo.completed ? 'checked' : ''}
                onchange="toggleTodo(${todo.id})"
            >
            <div class="todo-content">
                <div class="todo-title ${todo.completed ? 'completed' : ''}">
                    ${escapeHtml(todo.title)}
                </div>
                ${todo.description ? `<div class="todo-description">${escapeHtml(todo.description)}</div>` : ''}
                <div class="todo-date">
                    Created: ${new Date(todo.createdAt).toLocaleDateString()}
                    ${todo.updatedAt ? ` | Updated: ${new Date(todo.updatedAt).toLocaleDateString()}` : ''}
                </div>
            </div>
            <div class="todo-actions">
                <button class="edit-btn" onclick="editTodo(${todo.id})">Edit</button>
                <button class="delete-btn" onclick="deleteTodo(${todo.id})">Delete</button>
            </div>
        </div>
    `).join('');

    updateStats();
}

function getFilteredTodos() {
    switch (currentFilter) {
        case 'active':
            return todos.filter(todo => !todo.completed);
        case 'completed':
            return todos.filter(todo => todo.completed);
        default:
            return todos;
    }
}

function updateStats() {
    const activeCount = todos.filter(todo => !todo.completed).length;
    const totalCount = todos.length;
    document.getElementById('todoCount').textContent = 
        `${activeCount} ${activeCount === 1 ? 'item' : 'items'} left (${totalCount} total)`;
}

async function addTodo() {
    const titleInput = document.getElementById('todoTitle');
    const descriptionInput = document.getElementById('todoDescription');
    const title = titleInput.value.trim();
    const description = descriptionInput.value.trim();

    if (!title) {
        alert('Please enter a todo title');
        return;
    }

    try {
        const response = await fetch('/api/todos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ title, description }),
        });

        if (response.ok) {
            titleInput.value = '';
            descriptionInput.value = '';
            await loadTodos();
        }
    } catch (error) {
        console.error('Error adding todo:', error);
    }
}

async function toggleTodo(id) {
    const todo = todos.find(t => t.id === id);
    if (!todo) return;

    try {
        const response = await fetch(`/api/todos/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ completed: !todo.completed }),
        });

        if (response.ok) {
            await loadTodos();
        }
    } catch (error) {
        console.error('Error toggling todo:', error);
    }
}

async function editTodo(id) {
    const todo = todos.find(t => t.id === id);
    if (!todo) return;

    const newTitle = prompt('Edit todo title:', todo.title);
    if (newTitle === null) return;

    const newDescription = prompt('Edit todo description:', todo.description || '');

    try {
        const response = await fetch(`/api/todos/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                title: newTitle.trim(),
                description: newDescription ? newDescription.trim() : ''
            }),
        });

        if (response.ok) {
            await loadTodos();
        }
    } catch (error) {
        console.error('Error editing todo:', error);
    }
}

async function deleteTodo(id) {
    if (!confirm('Are you sure you want to delete this todo?')) {
        return;
    }

    try {
        const response = await fetch(`/api/todos/${id}`, {
            method: 'DELETE',
        });

        if (response.ok) {
            await loadTodos();
        }
    } catch (error) {
        console.error('Error deleting todo:', error);
    }
}

function filterTodos(filter) {
    currentFilter = filter;
    
    // Update active filter button
    document.querySelectorAll('.filters button').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    renderTodos();
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Allow adding todos with Enter key
document.getElementById('todoTitle').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        addTodo();
    }
});

document.getElementById('todoDescription').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        addTodo();
    }
});
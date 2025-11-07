document.addEventListener('DOMContentLoaded', function() {
    initCMS();
});

function initCMS() {
    initFormEnhancements();
    initDeleteConfirmations();
    initTextareaEnhancements();
    initDashboardInteractions();
    initMobileMenu();
    initFlashMessages();
}

// Form enhancements and validations
function initFormEnhancements() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Add basic form validation
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
        
        // Add real-time validation for required fields
        const requiredFields = form.querySelectorAll('input[required], textarea[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                validateField(this);
            });
            
            field.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
    });
}

// Form validation functions
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('input[required], textarea[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('name') || 'This field';
    
    if (field.hasAttribute('required') && value === '') {
        showFieldError(field, `${fieldName} is required`);
        return false;
    }
    
    // Additional validation rules can be added here
    if (field.type === 'email' && value !== '') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    clearFieldError(field);
    return true;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('error-field');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        color: #d32f2f;
        font-size: 0.8em;
        margin-top: 5px;
        padding: 2px 5px;
        background: #ffebee;
        border-radius: 3px;
    `;
    
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('error-field');
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Delete confirmation enhancements
function initDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('button[type="submit"][onclick*="confirm"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            const message = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
            
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
            
            // Add loading state
            this.disabled = true;
            this.textContent = 'Deleting...';
        });
    });
}

// Textarea enhancements (character count, auto-resize)
function initTextareaEnhancements() {
    const textareas = document.querySelectorAll('textarea');
    
    textareas.forEach(textarea => {
        // Auto-resize for content textarea
        if (textarea.name === 'content') {
            textarea.addEventListener('input', autoResizeTextarea);
            // Initial resize
            autoResizeTextarea.call(textarea);
        }
        
        // Character count for excerpt
        if (textarea.name === 'excerpt') {
            addCharacterCount(textarea, 160);
        }
        
        // Character count for title (if it were a textarea)
        if (textarea.name === 'title') {
            addCharacterCount(textarea, 255);
        }
    });
}

function autoResizeTextarea() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
}

function addCharacterCount(textarea, maxLength) {
    const container = textarea.parentNode;
    const counter = document.createElement('div');
    counter.className = 'character-count';
    counter.style.cssText = `
        font-size: 0.8em;
        color: #666;
        text-align: right;
        margin-top: 5px;
    `;
    
    function updateCounter() {
        const length = textarea.value.length;
        counter.textContent = `${length}/${maxLength} characters`;
        
        if (length > maxLength * 0.9) {
            counter.style.color = '#ff9800';
        } else {
            counter.style.color = '#666';
        }
        
        if (length > maxLength) {
            counter.style.color = '#f44336';
            counter.textContent += ' - Too long!';
        }
    }
    
    textarea.addEventListener('input', updateCounter);
    container.appendChild(counter);
    updateCounter();
}

// Dashboard interactions
function initDashboardInteractions() {
    // Quick edit functionality
    const postTitles = document.querySelectorAll('.dashboard table td:first-child');
    
    postTitles.forEach(titleCell => {
        titleCell.addEventListener('dblclick', function() {
            const postId = this.closest('tr').querySelector('input[name="delete_id"]').value;
            window.location.href = `edit_post.php?id=${postId}`;
        });
        
        // Add hover effect
        titleCell.style.cursor = 'pointer';
        titleCell.title = 'Double-click to edit';
    });
    
    // Quick status toggle
    const statusCells = document.querySelectorAll('.dashboard table td:nth-child(3)');
    
    statusCells.forEach(cell => {
        const statusText = cell.textContent.trim().toLowerCase();
        if (statusText === 'draft') {
            cell.style.color = '#ff9800';
            cell.style.fontWeight = 'bold';
        } else if (statusText === 'published') {
            cell.style.color = '#4caf50';
            cell.style.fontWeight = 'bold';
        }
    });
}

// Mobile menu functionality
function initMobileMenu() {
    const nav = document.querySelector('nav');
    if (!nav) return;
    
    // Create mobile menu button
    const menuButton = document.createElement('button');
    menuButton.innerHTML = '☰';
    menuButton.className = 'mobile-menu-button';
    menuButton.style.cssText = `
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 1.5em;
        cursor: pointer;
        padding: 5px 10px;
    `;
    
    const navContainer = nav.querySelector('.container');
    navContainer.insertBefore(menuButton, navContainer.querySelector('ul'));
    
    const navList = nav.querySelector('ul');
    navList.classList.add('mobile-nav');
    
    // Toggle mobile menu
    menuButton.addEventListener('click', function() {
        navList.classList.toggle('mobile-nav-open');
    });
    
    // Add responsive styles
    const style = document.createElement('style');
    style.textContent = `
        @media (max-width: 768px) {
            .mobile-menu-button {
                display: block !important;
            }
            
            .mobile-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #333;
                flex-direction: column;
                padding: 10px 0;
            }
            
            .mobile-nav.mobile-nav-open {
                display: flex;
            }
            
            .mobile-nav li {
                margin: 0;
                text-align: center;
            }
            
            .mobile-nav li a {
                display: block;
                padding: 10px 20px;
            }
            
            nav .container {
                position: relative;
            }
        }
    `;
    document.head.appendChild(style);
}

// Flash message auto-dismiss
function initFlashMessages() {
    const flashMessages = document.querySelectorAll('.success, .error');
    
    flashMessages.forEach(message => {
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (message.parentNode) {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (message.parentNode) {
                        message.remove();
                    }
                }, 500);
            }
        }, 5000);
        
        // Add close button
        const closeButton = document.createElement('button');
        closeButton.innerHTML = '×';
        closeButton.style.cssText = `
            background: none;
            border: none;
            font-size: 1.2em;
            cursor: pointer;
            float: right;
            padding: 0;
            margin-left: 10px;
        `;
        
        closeButton.addEventListener('click', function() {
            message.remove();
        });
        
        message.style.position = 'relative';
        message.appendChild(closeButton);
    });
}

// Rich text editor simulation (basic)
function initRichTextEditor() {
    const contentTextarea = document.querySelector('textarea[name="content"]');
    if (!contentTextarea) return;
    
    // Create toolbar
    const toolbar = document.createElement('div');
    toolbar.className = 'rich-text-toolbar';
    toolbar.style.cssText = `
        background: #f5f5f5;
        padding: 10px;
        border: 1px solid #ddd;
        border-bottom: none;
        border-radius: 3px 3px 0 0;
    `;
    
    const buttons = [
        { text: 'B', title: 'Bold', action: () => wrapSelection('**', '**') },
        { text: 'I', title: 'Italic', action: () => wrapSelection('*', '*') },
        { text: 'H2', title: 'Heading', action: () => wrapSelection('## ', '') },
        { text: '🔗', title: 'Link', action: insertLink },
        { text: '📷', title: 'Image', action: insertImage }
    ];
    
    buttons.forEach(btn => {
        const button = document.createElement('button');
        button.textContent = btn.text;
        button.title = btn.title;
        button.type = 'button';
        button.style.cssText = `
            background: white;
            border: 1px solid #ddd;
            padding: 5px 10px;
            margin-right: 5px;
            cursor: pointer;
            border-radius: 3px;
        `;
        button.addEventListener('click', btn.action);
        toolbar.appendChild(button);
    });
    
    contentTextarea.parentNode.insertBefore(toolbar, contentTextarea);
    
    function wrapSelection(before, after) {
        const start = contentTextarea.selectionStart;
        const end = contentTextarea.selectionEnd;
        const selectedText = contentTextarea.value.substring(start, end);
        const newText = before + selectedText + after;
        
        contentTextarea.value = contentTextarea.value.substring(0, start) + 
                               newText + 
                               contentTextarea.value.substring(end);
        
        // Restore selection
        contentTextarea.selectionStart = start + before.length;
        contentTextarea.selectionEnd = start + before.length + selectedText.length;
        contentTextarea.focus();
    }
    
    function insertLink() {
        const url = prompt('Enter URL:');
        if (url) {
            const text = prompt('Enter link text:', url);
            wrapSelection(`[${text || url}](${url})`, '');
        }
    }
    
    function insertImage() {
        const url = prompt('Enter image URL:');
        if (url) {
            const alt = prompt('Enter alt text:');
            wrapSelection(`![${alt || 'image'}](${url})`, '');
        }
    }
}

// Search functionality for dashboard
function initSearchFunctionality() {
    const dashboard = document.querySelector('.dashboard');
    if (!dashboard) return;
    
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Search posts...';
    searchInput.style.cssText = `
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 3px;
        margin-bottom: 20px;
        width: 100%;
        max-width: 300px;
    `;
    
    const actionsDiv = dashboard.querySelector('.dashboard-actions');
    if (actionsDiv) {
        actionsDiv.appendChild(searchInput);
    } else {
        dashboard.insertBefore(searchInput, dashboard.querySelector('h3'));
    }
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tables = dashboard.querySelectorAll('table');
        
        tables.forEach(table => {
            const rows = table.querySelectorAll('tbody tr');
            let visibleRows = 0;
            
            rows.forEach(row => {
                const title = row.querySelector('td:first-child').textContent.toLowerCase();
                if (title.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show/hide table based on results
            const tableContainer = table.closest('div') || table.parentNode;
            const noResults = tableContainer.querySelector('.no-results');
            
            if (visibleRows === 0) {
                if (!noResults) {
                    const message = document.createElement('p');
                    message.className = 'no-results';
                    message.textContent = 'No posts found matching your search.';
                    message.style.cssText = 'text-align: center; padding: 20px; color: #666;';
                    table.parentNode.insertBefore(message, table.nextSibling);
                }
                table.style.display = 'none';
            } else {
                if (noResults) noResults.remove();
                table.style.display = '';
            }
        });
    });
}

// Export functions for potential use in other scripts
window.CMS = {
    validateForm,
    validateField,
    showFieldError,
    clearFieldError,
    autoResizeTextarea,
    addCharacterCount
};

// Initialize rich text editor and search when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    initRichTextEditor();
    initSearchFunctionality();
});

// Add some basic CSS through JavaScript for dynamic elements
const dynamicStyles = `
    .error-field {
        border-color: #d32f2f !important;
        background-color: #ffebee !important;
    }
    
    .field-error {
        color: #d32f2f;
        font-size: 0.8em;
        margin-top: 5px;
        padding: 2px 5px;
        background: #ffebee;
        border-radius: 3px;
    }
    
    .character-count {
        font-size: 0.8em;
        color: #666;
        text-align: right;
        margin-top: 5px;
    }
    
    @media (max-width: 768px) {
        .mobile-menu-button {
            display: block !important;
        }
        
        .mobile-nav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #333;
            flex-direction: column;
            padding: 10px 0;
        }
        
        .mobile-nav.mobile-nav-open {
            display: flex;
        }
        
        .mobile-nav li {
            margin: 0;
            text-align: center;
        }
        
        .mobile-nav li a {
            display: block;
            padding: 10px 20px;
        }
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = dynamicStyles;
document.head.appendChild(styleSheet);
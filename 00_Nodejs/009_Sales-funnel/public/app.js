// API Base URL
const API_BASE = '/api/leads';

// DOM Elements
const leadsTableBody = document.getElementById('leadsTableBody');
const statsContainer = document.getElementById('statsContainer');
const leadForm = document.getElementById('leadForm');
const leadFormElement = document.getElementById('leadFormElement');
const formTitle = document.getElementById('formTitle');

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    loadLeads();
    loadFunnelStats();
});

// Load all leads
async function loadLeads() {
    try {
        const response = await fetch(API_BASE);
        const leads = await response.json();
        displayLeads(leads);
    } catch (error) {
        console.error('Error loading leads:', error);
        leadsTableBody.innerHTML = '<tr><td colspan="6">Error loading leads</td></tr>';
    }
}

// Load funnel statistics
async function loadFunnelStats() {
    try {
        const response = await fetch(`${API_BASE}/stats`);
        const stats = await response.json();
        displayFunnelStats(stats);
    } catch (error) {
        console.error('Error loading stats:', error);
        statsContainer.innerHTML = 'Error loading statistics';
    }
}

// Display leads in table
function displayLeads(leads) {
    if (leads.length === 0) {
        leadsTableBody.innerHTML = '<tr><td colspan="6">No leads found</td></tr>';
        return;
    }

    leadsTableBody.innerHTML = leads.map(lead => `
        <tr class="status-${lead.status}">
            <td>${lead.name}</td>
            <td>${lead.email}</td>
            <td>${lead.company || '-'}</td>
            <td><span class="status-badge">${lead.status}</span></td>
            <td>${lead.source.replace('_', ' ')}</td>
            <td>
                <button class="btn-edit" onclick="editLead('${lead._id}')">Edit</button>
                <button class="btn-danger" onclick="deleteLead('${lead._id}')">Delete</button>
            </td>
        </tr>
    `).join('');
}

// Display funnel statistics
function displayFunnelStats(stats) {
    const statusOrder = ['new', 'contacted', 'qualified', 'proposal', 'closed'];
    statsContainer.innerHTML = statusOrder.map(status => `
        <div class="stat-item">
            <div class="stat-value">${stats[status] || 0}</div>
            <div class="stat-label">${status}</div>
        </div>
    `).join('');
}

// Show add lead form
function showAddForm() {
    formTitle.textContent = 'Add New Lead';
    leadFormElement.reset();
    document.getElementById('leadId').value = '';
    leadForm.style.display = 'block';
}

// Hide form
function hideForm() {
    leadForm.style.display = 'none';
}

// Handle form submission
async function handleFormSubmit(event) {
    event.preventDefault();
    
    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        company: document.getElementById('company').value,
        status: document.getElementById('status').value,
        source: document.getElementById('source').value,
        notes: document.getElementById('notes').value
    };

    const leadId = document.getElementById('leadId').value;
    
    try {
        let response;
        if (leadId) {
            // Update existing lead
            response = await fetch(`${API_BASE}/${leadId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
        } else {
            // Create new lead
            response = await fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
        }

        if (response.ok) {
            hideForm();
            loadLeads();
            loadFunnelStats();
        } else {
            alert('Error saving lead');
        }
    } catch (error) {
        console.error('Error saving lead:', error);
        alert('Error saving lead');
    }
}

// Edit lead
async function editLead(leadId) {
    try {
        const response = await fetch(`${API_BASE}/${leadId}`);
        const lead = await response.json();
        
        document.getElementById('leadId').value = lead._id;
        document.getElementById('name').value = lead.name;
        document.getElementById('email').value = lead.email;
        document.getElementById('phone').value = lead.phone || '';
        document.getElementById('company').value = lead.company || '';
        document.getElementById('status').value = lead.status;
        document.getElementById('source').value = lead.source;
        document.getElementById('notes').value = lead.notes || '';
        
        formTitle.textContent = 'Edit Lead';
        leadForm.style.display = 'block';
    } catch (error) {
        console.error('Error loading lead:', error);
        alert('Error loading lead');
    }
}

// Delete lead
async function deleteLead(leadId) {
    if (!confirm('Are you sure you want to delete this lead?')) {
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/${leadId}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            loadLeads();
            loadFunnelStats();
        } else {
            alert('Error deleting lead');
        }
    } catch (error) {
        console.error('Error deleting lead:', error);
        alert('Error deleting lead');
    }
}
const fs = require('fs');
const path = require('path');

class Contact {
  constructor() {
    this.dataFile = path.join(__dirname, '../data/contacts.json');
    this.ensureDataFileExists();
  }

  ensureDataFileExists() {
    if (!fs.existsSync(this.dataFile)) {
      fs.writeFileSync(this.dataFile, '[]', 'utf8');
    }
  }

  readContacts() {
    try {
      const data = fs.readFileSync(this.dataFile, 'utf8');
      return JSON.parse(data);
    } catch (error) {
      console.log('Error reading contacts:', error.message);
      return [];
    }
  }

  writeContacts(contacts) {
    try {
      fs.writeFileSync(this.dataFile, JSON.stringify(contacts, null, 2), 'utf8');
      return true;
    } catch (error) {
      console.log('Error writing contacts:', error.message);
      return false;
    }
  }

  addContact(name, phone, email, address = '') {
    const contacts = this.readContacts();
    
    // Check if contact already exists
    const existingContact = contacts.find(contact => 
      contact.phone === phone || contact.email === email
    );
    
    if (existingContact) {
      return { success: false, message: 'Contact with this phone or email already exists' };
    }

    const newContact = {
      id: Date.now().toString(),
      name,
      phone,
      email,
      address,
      createdAt: new Date().toISOString()
    };

    contacts.push(newContact);
    const success = this.writeContacts(contacts);
    
    return { 
      success, 
      message: success ? 'Contact added successfully!' : 'Failed to add contact',
      contact: newContact
    };
  }

  getAllContacts() {
    return this.readContacts();
  }

  getContactById(id) {
    const contacts = this.readContacts();
    return contacts.find(contact => contact.id === id);
  }

  searchContacts(searchTerm) {
    const contacts = this.readContacts();
    const term = searchTerm.toLowerCase();
    
    return contacts.filter(contact => 
      contact.name.toLowerCase().includes(term) ||
      contact.phone.includes(term) ||
      contact.email.toLowerCase().includes(term)
    );
  }

  updateContact(id, updatedData) {
    const contacts = this.readContacts();
    const contactIndex = contacts.findIndex(contact => contact.id === id);
    
    if (contactIndex === -1) {
      return { success: false, message: 'Contact not found' };
    }

    // Keep the original ID and creation date
    contacts[contactIndex] = {
      ...contacts[contactIndex],
      ...updatedData,
      id: contacts[contactIndex].id, // Ensure ID doesn't change
      createdAt: contacts[contactIndex].createdAt // Keep original creation date
    };

    const success = this.writeContacts(contacts);
    
    return { 
      success, 
      message: success ? 'Contact updated successfully!' : 'Failed to update contact',
      contact: contacts[contactIndex]
    };
  }

  deleteContact(id) {
    const contacts = this.readContacts();
    const contactIndex = contacts.findIndex(contact => contact.id === id);
    
    if (contactIndex === -1) {
      return { success: false, message: 'Contact not found' };
    }

    const deletedContact = contacts.splice(contactIndex, 1)[0];
    const success = this.writeContacts(contacts);
    
    return { 
      success, 
      message: success ? 'Contact deleted successfully!' : 'Failed to delete contact',
      contact: deletedContact
    };
  }
}

module.exports = Contact;
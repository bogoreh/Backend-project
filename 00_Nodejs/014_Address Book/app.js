const readline = require('readline');
const Contact = require('./models/Contact');

class AddressBookApp {
  constructor() {
    this.contactManager = new Contact();
    this.rl = readline.createInterface({
      input: process.stdin,
      output: process.stdout
    });
  }

  displayMenu() {
    console.log('\n=== ADDRESS BOOK ===');
    console.log('1. Add Contact');
    console.log('2. View All Contacts');
    console.log('3. Search Contacts');
    console.log('4. Update Contact');
    console.log('5. Delete Contact');
    console.log('6. Exit');
    console.log('===================');
  }

  askQuestion(question) {
    return new Promise((resolve) => {
      this.rl.question(question, resolve);
    });
  }

  async addContact() {
    console.log('\n--- Add New Contact ---');
    
    const name = await this.askQuestion('Name: ');
    const phone = await this.askQuestion('Phone: ');
    const email = await this.askQuestion('Email: ');
    const address = await this.askQuestion('Address (optional): ');

    const result = this.contactManager.addContact(name, phone, email, address);
    console.log(result.message);
  }

  viewAllContacts() {
    console.log('\n--- All Contacts ---');
    const contacts = this.contactManager.getAllContacts();
    
    if (contacts.length === 0) {
      console.log('No contacts found.');
      return;
    }

    contacts.forEach((contact, index) => {
      console.log(`${index + 1}. ${contact.name}`);
      console.log(`   Phone: ${contact.phone}`);
      console.log(`   Email: ${contact.email}`);
      if (contact.address) console.log(`   Address: ${contact.address}`);
      console.log(`   ID: ${contact.id}`);
      console.log('   ---');
    });
  }

  async searchContacts() {
    console.log('\n--- Search Contacts ---');
    const searchTerm = await this.askQuestion('Enter search term: ');
    
    const results = this.contactManager.searchContacts(searchTerm);
    
    if (results.length === 0) {
      console.log('No contacts found matching your search.');
      return;
    }

    console.log(`Found ${results.length} contact(s):`);
    results.forEach((contact, index) => {
      console.log(`${index + 1}. ${contact.name} (${contact.phone}) - ${contact.email}`);
    });
  }

  async updateContact() {
    console.log('\n--- Update Contact ---');
    this.viewAllContacts();
    
    const contactId = await this.askQuestion('Enter contact ID to update: ');
    const contact = this.contactManager.getContactById(contactId);
    
    if (!contact) {
      console.log('Contact not found.');
      return;
    }

    console.log(`\nCurrent details for ${contact.name}:`);
    console.log(`1. Name: ${contact.name}`);
    console.log(`2. Phone: ${contact.phone}`);
    console.log(`3. Email: ${contact.email}`);
    console.log(`4. Address: ${contact.address}`);

    const name = await this.askQuestion(`New name (${contact.name}): `) || contact.name;
    const phone = await this.askQuestion(`New phone (${contact.phone}): `) || contact.phone;
    const email = await this.askQuestion(`New email (${contact.email}): `) || contact.email;
    const address = await this.askQuestion(`New address (${contact.address}): `) || contact.address;

    const result = this.contactManager.updateContact(contactId, {
      name, phone, email, address
    });

    console.log(result.message);
  }

  async deleteContact() {
    console.log('\n--- Delete Contact ---');
    this.viewAllContacts();
    
    const contactId = await this.askQuestion('Enter contact ID to delete: ');
    const contact = this.contactManager.getContactById(contactId);
    
    if (!contact) {
      console.log('Contact not found.');
      return;
    }

    const confirm = await this.askQuestion(`Are you sure you want to delete ${contact.name}? (y/N): `);
    
    if (confirm.toLowerCase() === 'y') {
      const result = this.contactManager.deleteContact(contactId);
      console.log(result.message);
    } else {
      console.log('Deletion cancelled.');
    }
  }

  async run() {
    console.log('Welcome to Address Book!');
    
    while (true) {
      this.displayMenu();
      const choice = await this.askQuestion('Choose an option (1-6): ');

      switch (choice) {
        case '1':
          await this.addContact();
          break;
        case '2':
          this.viewAllContacts();
          break;
        case '3':
          await this.searchContacts();
          break;
        case '4':
          await this.updateContact();
          break;
        case '5':
          await this.deleteContact();
          break;
        case '6':
          console.log('Goodbye!');
          this.rl.close();
          return;
        default:
          console.log('Invalid option. Please try again.');
      }
    }
  }
}

// Start the application
const app = new AddressBookApp();
app.run().catch(console.error);
// Simple in-memory database for demonstration
// In a real project, you'd use MongoDB, MySQL, etc.

let posts = [
  {
    id: '1',
    title: 'Welcome to My Blog',
    content: 'This is the first post in our simple blog system.',
    author: 'Admin',
    createdAt: new Date('2024-01-15'),
    updatedAt: new Date('2024-01-15')
  },
  {
    id: '2',
    title: 'Getting Started with Node.js',
    content: 'Node.js is a powerful JavaScript runtime built on Chrome\'s V8 JavaScript engine.',
    author: 'Admin',
    createdAt: new Date('2024-01-16'),
    updatedAt: new Date('2024-01-16')
  }
];

module.exports = {
  posts
};
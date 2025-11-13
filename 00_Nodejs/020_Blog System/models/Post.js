const { v4: uuidv4 } = require('uuid');
const { posts } = require('../config/database');

class Post {
  static getAll() {
    return posts.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
  }

  static getById(id) {
    return posts.find(post => post.id === id);
  }

  static create(postData) {
    const newPost = {
      id: uuidv4(),
      title: postData.title,
      content: postData.content,
      author: postData.author || 'Anonymous',
      createdAt: new Date(),
      updatedAt: new Date()
    };
    
    posts.push(newPost);
    return newPost;
  }

  static update(id, postData) {
    const postIndex = posts.findIndex(post => post.id === id);
    if (postIndex === -1) return null;

    posts[postIndex] = {
      ...posts[postIndex],
      title: postData.title,
      content: postData.content,
      author: postData.author || posts[postIndex].author,
      updatedAt: new Date()
    };

    return posts[postIndex];
  }

  static delete(id) {
    const postIndex = posts.findIndex(post => post.id === id);
    if (postIndex === -1) return false;

    posts.splice(postIndex, 1);
    return true;
  }
}

module.exports = Post;
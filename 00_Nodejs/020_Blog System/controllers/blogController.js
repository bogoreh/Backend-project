const Post = require('../models/Post');

exports.getAllPosts = (req, res) => {
  const posts = Post.getAll();
  res.render('index', { posts, title: 'All Posts' });
};

exports.getPost = (req, res) => {
  const post = Post.getById(req.params.id);
  if (!post) {
    return res.status(404).render('404', { title: 'Post Not Found' });
  }
  res.render('post', { post, title: post.title });
};

exports.showCreateForm = (req, res) => {
  res.render('create', { title: 'Create New Post' });
};

exports.createPost = (req, res) => {
  const { title, content, author } = req.body;
  
  if (!title || !content) {
    return res.status(400).render('create', {
      title: 'Create New Post',
      error: 'Title and content are required',
      post: { title, content, author }
    });
  }

  const newPost = Post.create({ title, content, author });
  res.redirect(`/posts/${newPost.id}`);
};

exports.showEditForm = (req, res) => {
  const post = Post.getById(req.params.id);
  if (!post) {
    return res.status(404).render('404', { title: 'Post Not Found' });
  }
  res.render('edit', { post, title: 'Edit Post' });
};

exports.updatePost = (req, res) => {
  const { title, content, author } = req.body;
  
  if (!title || !content) {
    const post = Post.getById(req.params.id);
    return res.status(400).render('edit', {
      title: 'Edit Post',
      error: 'Title and content are required',
      post: { ...post, title, content, author }
    });
  }

  const updatedPost = Post.update(req.params.id, { title, content, author });
  if (!updatedPost) {
    return res.status(404).render('404', { title: 'Post Not Found' });
  }

  res.redirect(`/posts/${updatedPost.id}`);
};

exports.deletePost = (req, res) => {
  const deleted = Post.delete(req.params.id);
  if (!deleted) {
    return res.status(404).render('404', { title: 'Post Not Found' });
  }
  res.redirect('/posts');
};
const users = [];

class User {
  constructor(id, username, email, password) {
    this.id = id;
    this.username = username;
    this.email = email;
    this.password = password;
  }

  static create(userData) {
    const user = new User(
      users.length + 1,
      userData.username,
      userData.email,
      userData.password
    );
    users.push(user);
    return user;
  }

  static findByEmail(email) {
    return users.find(user => user.email === email);
  }

  static findById(id) {
    return users.find(user => user.id === id);
  }
}

module.exports = User;
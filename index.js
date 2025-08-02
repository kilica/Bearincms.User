console.log('BearinUser module initialized');

class BearinUser {
  constructor() {
    this.users = [];
  }

  addUser(user) {
    this.users.push(user);
    return user;
  }

  getUsers() {
    return this.users;
  }
}

module.exports = BearinUser;

import { reactive } from 'vue';

export const authStore = reactive({
  isLoggedIn: false,
  user: {
    name: 'Ім\'я'
  },
  setUser(userData) {
    this.isLoggedIn = true;
    this.user.name = userData.name;
  },
  clearUser() {
    this.isLoggedIn = false;
    this.user.name = 'Ім\'я';
  }
});

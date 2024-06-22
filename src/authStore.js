import { reactive } from 'vue';

export const authStore = reactive({
  isLoggedIn: false,
  user: null,
  setUser(userData) {
    this.user = userData;
    this.isLoggedIn = true;
  },
  clearUser() {
    this.user = null;
    this.isLoggedIn = false;
  }
});

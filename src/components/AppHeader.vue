<<<<<<< HEAD
<template>
  <header class="header" :class="{ 'sticky': isSticky }">
    <div class="brand">
      <router-link to="/" class="hotel-name">AMETHYST</router-link>
    </div>
    <nav class="nav" :class="{ 'open': isMenuOpen }">
      <router-link to="/rooms" class="nav-link" exact-active-class="active">Кімнати</router-link>
      <router-link to="/location" class="nav-link" exact-active-class="active">Локація</router-link>
      <router-link to="/booking" class="nav-link" exact-active-class="active">Бронювання</router-link>
      <router-link to="/contacts" class="nav-link" exact-active-class="active">Про нас</router-link>
      <template v-if="isLoggedIn">
        <span class="welcome-message">Вітаємо, {{ user.name }}!</span>
        <router-link to="/user-dashboard" class="nav-link" exact-active-class="active">Профіль</router-link>
      </template>
      <template v-else>
        <router-link to="/login" class="nav-link" exact-active-class="active">Вхід</router-link>
        <router-link to="/registration" class="nav-link" exact-active-class="active">Реєстрація</router-link>
      </template>
    </nav>
    <button class="menu-toggle" @click="toggleMenu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  data() {
    return {
      isSticky: false,
      isLoggedIn: false,
      isMenuOpen: false,
      user: {
        name: 'Ім\'я'
      }
    };
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll);
    this.checkAuth();
  },
  unmounted() {
    window.removeEventListener('scroll', this.handleScroll);
  },
  methods: {
    handleScroll() {
      this.isSticky = window.scrollY > 0;
    },
    checkAuth() {
      const user = localStorage.getItem('user');
      if (user) {
        this.isLoggedIn = true;
        this.user = JSON.parse(user);
      }
    },
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap');

html, body {
  margin: 0;
  padding: 0;
  width: 100%;
  box-sizing: border-box;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background-color: #6a0dad;
  color: #fff;
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  transition: top 0.3s;
}

.brand {
  font-size: 24px;
  font-weight: bold;
}

.hotel-name {
  color: #fff;
  text-decoration: none;
  font-family: 'Voltaire', sans-serif;
}

.nav {
  display: flex;
  font-size: 22px;
}

.nav-link {
  margin-left: 20px;
  margin-right: 40px;
  color: #fff;
  text-decoration: none;
  font-family: 'Gabriela', serif;
}

.auth {
  display: flex;
  align-items: center;
}

.auth-link {
  font-size: 22px;
  margin-left: 20px;
  margin-right: 40px;
  color: #fff;
  text-decoration: none;
  font-family: 'Gabriela', serif;
}

.welcome-message {
  font-size: 22px;
  margin-right: 20px;
  font-family: 'Gabriela', serif;
}

.active {
  border-bottom: 2px solid #fff;
  font-weight: bold;
}

.menu-toggle {
  display: none;
  flex-direction: column;
  justify-content: space-between;
  width: 30px;
  height: 21px;
  background: none;
  border: none;
  cursor: pointer;
  margin-right: 20px; 
}

.bar {
  height: 3px;
  width: 100%;
  background-color: #fff;
  border-radius: 10px;
}

@media (max-width: 768px) {
  .nav, .auth {
    display: none;
    flex-direction: column;
    position: absolute;
    top: 70px;
    left: 0;
    width: 100%;
    background-color: #6a0dad;
  }

  .nav.open, .auth.open {
    display: flex;
  }

  .nav-link, .auth-link {
    margin: 10px 0;
    text-align: center;
  }

  .menu-toggle {
    display: flex;
  }
}

@media (min-width: 769px) {
  .nav {
    margin: 0 auto; 
  }
}
</style>
=======
<template>
  <header class="header" :class="{ 'sticky': isSticky }">
    <div class="header-container">
      <div class="brand">
        <router-link to="/" class="hotel-name">AMETHYST</router-link>
      </div>
      <nav class="nav" :class="{ 'open': isMenuOpen }">
        <router-link to="/rooms" class="nav-link" exact-active-class="active">Кімнати</router-link>
        <router-link to="/location" class="nav-link" exact-active-class="active">Локація</router-link>
        <router-link to="/booking" class="nav-link" exact-active-class="active">Бронювання</router-link>
        <router-link to="/contacts" class="nav-link" exact-active-class="active">Про нас</router-link>
      </nav>
      <div class="auth-container" v-if="!authStore.isLoggedIn">
        <div class="auth-content">
          <img src="@/assets/image-removebg-preview (1аукау).png" alt="Login Icon" class="login-icon"/>
          <router-link to="/login" class="auth-link" exact-active-class="active">Увійти в кабінет</router-link>
        </div>
      </div>
      <template v-if="authStore.isLoggedIn">
        <span class="welcome-message">Вітаємо, {{ authStore.user.name }}!</span>
        <img src="@/assets/image-removebg-preview (1аукау).png" alt="Login Icon" class="login-icon"/>
        <router-link
          :to="authStore.user.role === 'admin' ? '/admin' : '/user'"
          class="nav-link"
          exact-active-class="active"
        >
          Профіль
        </router-link>
        <button class="logout-button" @click="logout">Вийти</button>
      </template>
      <button class="menu-toggle" @click="toggleMenu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </button>
    </div>
  </header>
</template>

<script>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import { authStore } from '@/authStore';

export default {
  name: 'Header',
  setup() {
    const router = useRouter();
    const isSticky = ref(false);
    const isMenuOpen = ref(false);

    const handleScroll = () => {
      isSticky.value = window.scrollY > 0;
    };

    const toggleMenu = () => {
      isMenuOpen.value = !isMenuOpen.value;
    };

    const logout = () => {
      localStorage.removeItem('userData');
      authStore.clearUser();
      router.push('/login');
    };

    const checkAuth = () => {
      const userData = localStorage.getItem('userData');
      if (userData) {
        authStore.setUser(JSON.parse(userData));
      } else {
        authStore.clearUser();
      }
    };

    watch(
      () => authStore.isLoggedIn,
      (newVal) => {
        if (newVal) {
          // Do something if needed when user logs in
        } else {
          // Do something if needed when user logs out
        }
      },
      { immediate: true }
    );

    window.addEventListener('scroll', handleScroll);
    checkAuth();

    return {
      isSticky,
      isMenuOpen,
      toggleMenu,
      logout,
      authStore
    };
  },
  unmounted() {
    window.removeEventListener('scroll', this.handleScroll);
  }
};
</script>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap');

html, body {
  margin: 0;
  padding: 0;
  width: 100%;
  box-sizing: border-box;
}

.header {
  display: flex;
  justify-content: center;
  padding: 10px 20px;
  background-color: #6a0dad;
  color: #fff;
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  transition: top 0.3s;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  max-width: 1200px;
}

.brand {
  font-size: 24px;
  font-weight: bold;
}

.hotel-name {
  margin-right:200px;
  color: #fff;
  text-decoration: none;
  font-family: 'Voltaire', sans-serif;
}

.nav {
  display: flex;
  font-size: 18px;
  margin-left: 20px;
  flex-grow: 1;
}

.nav-link {
  margin-left: 20px;
  margin-right: 20px;
  color: #fff;
  text-decoration: none;
  font-family: 'Gabriela', serif;
  transition: color 0.3s ease-in-out;
}

.nav-link:hover {
  color: #d4c1ff;
}

.login-icon {
  width: 24px;
  height: 24px;
}

.auth-container {
  display: flex;
  align-items: center;
}

.auth-content {
  display: flex;
  align-items: center;
  margin-left: auto;
}

.auth-content img {
  margin-right: 10px;
}

.auth-content .auth-link {
  font-size: 18px;
  color: #fff;
  text-decoration: none;
  font-family: 'Gabriela', serif;
  transition: color 0.3s ease-in-out;
}

.auth-content .auth-link:hover {
  color: #d4c1ff;
}

.logout-button {
  padding: 8px 16px;
  background-color: #c0392b;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
  transition: background-color 0.3s ease-in-out;
}

.logout-button:hover {
  background-color: #e74c3c;
}

.welcome-message {
  font-size: 18px;
  margin-right: 10px;
  font-family: 'Gabriela', serif;
  margin-left: 20px;
}

.active {
  border-bottom: 2px solid #fff;
  font-weight: bold;
}

.menu-toggle {
  display: none;
  flex-direction: column;
  justify-content: space-between;
  width: 30px;
  height: 21px;
  background: none;
  border: none;
  cursor: pointer;
}

.bar {
  height: 3px;
  width: 100%;
  background-color: #fff;
  border-radius: 10px;
}

@media (max-width: 768px) {
  .nav, .auth-container {
    display: none;
    flex-direction: column;
    position: absolute;
    top: 60px;
    left: 0;
    width: 100%;
    background-color: #6a0dad;
    z-index: 999; /* Ensure the menu appears above other elements */
    padding: 10px 0;
  }

  .nav.open, .auth-container.open {
    display: flex;
  }

  .nav-link, .auth-link {
    margin: 10px 0;
    text-align: center;
  }

  .menu-toggle {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 30px;
    height: 21px;
    background: none;
    border: none;
    cursor: pointer;
  }

  .welcome-message {
    display: none;
  }

  .auth-content {
    display: none;
  }

  .login-icon {
    display: none;
  }
}

@media (min-width: 769px) {
  .nav {
    margin: 0 auto;
  }
}
</style>
>>>>>>> database

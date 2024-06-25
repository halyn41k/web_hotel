/* eslint-disable import/no-unresolved */
/* eslint-disable */ 
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
          <img :src="headerImages.loginIcon" alt="Login Icon" class="login-icon"/>
          <img :src="headerImages.loginIcon" alt="Login Icon" class="login-icon"/>
          <router-link to="/login" class="auth-link" exact-active-class="active">Увійти в кабінет</router-link>
        </div>
      </div>
      <template v-if="authStore.isLoggedIn">
        <span class="welcome-message">Вітаємо, {{ authStore.user.name }}!</span>
        <img :src="headerImages.loginIcon" alt="Login Icon" class="login-icon"/>
        <router-link
          :to="getProfileLink"
          class="nav-link"
          exact-active-class="active">
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
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { authStore } from '@/authStore';

export default {
  name: 'AppHeader',
  name: 'AppHeader',
  setup() {
    const router = useRouter();
    const isSticky = ref(false);
    const isMenuOpen = ref(false);
    const headerImages = ref({
      loginIcon: ''
    });
    const headerImages = ref({
      loginIcon: ''
    });

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
        console.log('User data set:', authStore.user); // Log user data
      } else {
        authStore.clearUser();
      }
    };

    const fetchHeaderImages = async () => {
      try {
        const response = await fetch('http://localhost/new-hotel-website/backend/get_images.php');
        const images = await response.json();
        const loginIconImage = images.find(img => img.category === 'header' && img.image_name === 'image-removebg-preview (1аукау).png');
        if (loginIconImage) {
          headerImages.value.loginIcon = getImageUrl(loginIconImage.image_name);
        } else {
          console.error('Login icon image not found.');
        }
      } catch (error) {
        console.error('Error fetching header images:', error);
      }
    };

    const getImageUrl = (imageName) => {
      return `http://localhost/new-hotel-website/src/assets/${imageName}`;
    };

    const getProfileLink = () => {
      console.log('User role:', authStore.user.role); // Log user role
      switch (authStore.user.role) {
        case 'admin':
          return '/admin';
        case 'manager':
          return '/manager';
        default:
          return '/user';
      }
    };

    watch(
      () => authStore.isLoggedIn,
      (newVal) => {
        if (newVal) {
          console.log('User logged in with role:', authStore.user.role); // Log role on login
        }
      },
      { immediate: true }
    );

    onMounted(() => {
      window.addEventListener('scroll', handleScroll);
      checkAuth();
      fetchHeaderImages();
    });

    onUnmounted(() => {
      window.removeEventListener('scroll', handleScroll);
    });

    return {
      isSticky,
      isMenuOpen,
      toggleMenu,
      logout,
      authStore,
      headerImages,
      getProfileLink
    };
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
  margin-right: 200px;
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
    margin-right: 15px;
  }

  .welcome-message {
    display: none;
  }

  .auth-content {
    display: flex;
  }

  .login-icon {
    display: none;
  }

  .profile-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
  }

  .logout-button {
    margin-top: 10px;
  }
}

@media (min-width: 769px) {
  .nav {
    margin: 0 auto;
  }
}
</style>

<template>
  <div class="login-wrapper">
    <div id="background-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
    <div class="login-form">
      <h2>Вхід</h2>
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="identifier">Електронна пошта або ім'я користувача:</label>
          <input type="text" v-model="identifier" @input="validateIdentifier" required>
          <span v-if="identifierError" class="error-message" v-show="identifierErrorVisible">{{ identifierError }}</span>
        </div>
        <div class="form-group">
          <label for="password">Пароль:</label>
          <div class="password-wrapper">
            <input :type="passwordFieldType" v-model="password" @input="validatePassword" required>
            <img :src="passwordToggleIcon" @click="togglePasswordVisibility" class="password-toggle-icon" />
          </div>
          <span v-if="passwordError" class="error-message" v-show="passwordErrorVisible">{{ passwordError }}</span>
        </div>
        <button type="submit" id="submit">Увійти</button>
        <div class="registration-link">
          <span>Ви ще не зареєстровані? </span>
          <router-link to="/registration" class="register-link">Зареєструватися</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppLogin',
  data() {
    return {
      identifier: '',
      password: '',
      identifierError: '',
      passwordError: '',
      passwordVisible: false,
      backgroundImage: '',
      eyeIcon: '',
      hideIcon: ''
    };
  },
  computed: {
    identifierErrorVisible() {
      return !!this.identifierError;
    },
    passwordErrorVisible() {
      return !!this.passwordError;
    },
    passwordFieldType() {
      return this.passwordVisible ? 'text' : 'password';
    },
    passwordToggleIcon() {
      return this.passwordVisible ? this.hideIcon : this.eyeIcon;
    }
  },
  methods: {
    async login() {
      if (!this.validateIdentifier()) return;
      if (!this.validatePassword()) return;

      try {
        const response = await fetch('http://localhost/new-hotel-website/backend/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            identifier: this.identifier,
            password: this.password
          })
        });
        const data = await response.json();
        if (data.success) {
          localStorage.setItem('userData', JSON.stringify(data.user));
          if (data.role === 'admin') {
            this.$router.push({ name: 'AdminDashboard' });
          } else if (data.role === 'manager') {
            this.$router.push({ name: 'ManagerDashboard' });
          } else {
            this.$router.push({ name: 'UserDashboard' });
          }
        } else {
          alert(data.error);
        }
      } catch (error) {
        console.error('Error during login:', error);
        alert('Помилка при вході');
      }
    },
    validateIdentifier() {
      if (!this.identifier.trim()) {
        this.identifierError = 'Електронна пошта або ім\'я користувача є обов\'язковими';
        return false;
      } else {
        this.identifierError = '';
        return true;
      }
    },
    validatePassword() {
      if (!this.password.trim()) {
        this.passwordError = 'Пароль є обов\'язковим';
        return false;
      } else if (this.password.trim().length < 8) {
        this.passwordError = 'Пароль повинен містити мінімум 8 символів';
        return false;
      } else {
        this.passwordError = '';
        return true;
      }
    },
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
    async fetchImages() {
      try {
        const response = await fetch('http://localhost/new-hotel-website/backend/get_images.php');
        const images = await response.json();

        const loginImage = images.find(img => img.category === 'login' && img.image_name === 'login.png');
        const eyeIconImage = images.find(img => img.category === 'login' && img.image_name === 'eye.png');
        const hideIconImage = images.find(img => img.category === 'login' && img.image_name === 'hide.png');

        if (loginImage) {
          this.backgroundImage = this.getImageUrl(loginImage.image_name);
        } else {
          console.error('Login background image not found.');
        }

        if (eyeIconImage) {
          this.eyeIcon = this.getImageUrl(eyeIconImage.image_name);
        } else {
          console.error('Eye icon image not found.');
        }

        if (hideIconImage) {
          this.hideIcon = this.getImageUrl(hideIconImage.image_name);
        } else {
          console.error('Hide icon image not found.');
        }
      } catch (error) {
        console.error('Error fetching images:', error);
      }
    },
    getImageUrl(imageName) {
      return `http://localhost/new-hotel-website/src/assets/${imageName}`;
    }
  },
  mounted() {
    this.fetchImages();
    document.title = 'Amethyst Hotel | Login';
  }
};
</script>

<style scoped>
.login-wrapper {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 20px;
  box-sizing: border-box;
}

#background-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  z-index: -1;
}

.login-form {
  width: 100%;
  max-width: 600px;
  padding: 20px;
  margin: 40px 20px; 
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  font-family: 'Gabriela', serif;
  box-sizing: border-box;
}

.login-form h2 {
  margin-bottom: 20px;
  color: #6a0dad;
  text-align: center;
  font-family: 'Gabriela', serif;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
  box-sizing: border-box;
}

.password-wrapper {
  position: relative;
}

.password-toggle-icon {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  cursor: pointer;
  width: 24px;
  height: 24px;
}

button {
  width: 100%;
  background-color: #e67e22;
  font-family: 'Gabriela', serif;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
  box-sizing: border-box;
}

button:hover {
  background-color: #d35400;
}

.error-message {
  color: red;
}

.registration-link {
  text-align: center;
  margin-top: 10px;
}

.register-link {
  color: #6a0dad;
  font-weight: bold;
  text-decoration: none;
}

.register-link:hover {
  text-decoration: underline;
}
</style>

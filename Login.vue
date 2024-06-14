<template>
  <div class="login-wrapper">
    <div id="background-image"></div>
    <div class="login-form">
      <h2>Вхід</h2>
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="email">Електронна пошта:</label>
          <input type="email" v-model="email" @input="validateEmail" required>
          <span v-if="emailError" class="error-message" v-show="emailErrorVisible">{{ emailError }}</span>
        </div>
        <div class="form-group">
          <label for="password">Пароль:</label>
          <input type="password" v-model="password" @input="validatePassword" required>
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
      email: '',
      password: '',
      emailError: '',
      passwordError: ''
    };
  },
  computed: {
    emailErrorVisible() {
      return !!this.emailError;
    },
    passwordErrorVisible() {
      return !!this.passwordError;
    }
  },
  methods: {
    async login() {
      if (!this.validateEmail()) return;
      if (!this.validatePassword()) return;

      try {
        const response = await fetch('http://localhost/new-hotel-website/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            email: this.email,
            password: this.password
          })
        });
        const data = await response.json();
        if (data.success) {
          localStorage.setItem('userData', JSON.stringify(data.user));
          this.$router.push({ name: 'UserDashboard' });
        } else {
          alert(data.error);
        }
      } catch (error) {
        alert('Помилка при вході');
      }
    },
    validateEmail() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!this.email.trim()) {
        this.emailError = 'Електронна пошта є обов\'язковою';
        return false;
      } else if (!emailRegex.test(this.email.trim())) {
        this.emailError = 'Введіть дійсну адресу електронної пошти';
        return false;
      } else {
        this.emailError = '';
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
    }
  },
  mounted() {
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
  background-image: url('@/assets/Знімок екрана 2024-06-03 111151.png');
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
  margin-top: 15px;
  text-align: center;
  font-family: 'Gabriela', serif;
}

.register-link {
  color: #6a0dad;
  text-decoration: none;
}

.register-link:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .login-form {
    padding: 15px;
  }

  button {
    font-size: 14px;
    padding: 8px 15px;
  }
}

@media (max-width: 480px) {
  .login-form {
    padding: 10px;
  }

  button {
    font-size: 12px;
    padding: 8px 10px;
  }
}
</style>

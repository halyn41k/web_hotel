<<<<<<< HEAD
<template>
  <div class="login-wrapper">
    <div id="background-image"></div>
    <div class="login-form">
      <h2>Вхід</h2>
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="email">Електронна пошта:</label>
          <input type="email" v-model="email" required>
        </div>
        <div class="form-group">
          <label for="password">Пароль:</label>
          <input type="password" v-model="password" required>
        </div>
        <button type="submit">Увійти</button>
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
      password: ''
    };
  },
  methods: {
    login() {
      alert('Вхід успішний!');
      this.$router.push('/user');
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap');

*,
*::before,
*::after {
  box-sizing: border-box;
}

.login-wrapper {
  position: relative;
  max-width: 100%;
  height: 100vh;
  overflow: hidden;
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
  max-width: 600px;
  margin: 200px auto 0 auto;
  padding: 20px;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  font-family: 'Gabriela', sans-serif;
  position: relative;
  z-index: 1;
}

.login-form h2 {
  font-family: 'Gabriela', serif;
  margin-bottom: 20px;
  color: #6a0dad;
  text-align: center;
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
}

button {
  background-color: #e67e22;
  font-family: 'Gabriela', serif;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
  width: 100%; 
}

button:hover {
  background-color: #d35400;
}

@media (max-width: 768px) {
  .login-form {
    margin: 50px 20px;
    padding: 15px;
  }

  .login-form h2 {
    font-size: 24px;
  }

  .form-group input {
    font-size: 14px;
  }

  button {
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .login-form {
    margin: 20px 10px;
    padding: 10px;
  }

  .login-form h2 {
    font-size: 20px;
  }

  .form-group input {
    font-size: 12px;
  }

  button {
    font-size: 12px;
  }
}
</style>
=======
<template>
  <div class="login-wrapper">
    <div id="background-image"></div>
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
      identifier: '',
      password: '',
      identifierError: '',
      passwordError: ''
    };
  },
  computed: {
    identifierErrorVisible() {
      return !!this.identifierError;
    },
    passwordErrorVisible() {
      return !!this.passwordError;
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
>>>>>>> database

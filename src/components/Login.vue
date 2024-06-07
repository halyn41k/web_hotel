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
  name: 'Login',
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    login() {
      // Логіка для обробки входу
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
  padding: 10px; /* Adjusted padding */
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
  width: 100%; /* Ensures button width adjusts in smaller screens */
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

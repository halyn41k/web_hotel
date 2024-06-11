<template>
  <div class="registration-wrapper">
    <div id="background-image"></div>
    <div class="registration-form">
      <h2>Реєстрація</h2>
      <form @submit.prevent="register">
        <div class="form-group">
          <label for="name">Ім'я:</label>
          <input type="text" v-model="name" required>
        </div>
        <div class="form-group">
          <label for="surname">Прізвище:</label>
          <input type="text" v-model="surname" required>
        </div>
        <div class="form-group">
          <label for="phone">Телефон:</label>
          <input type="tel" v-model="phone" required>
        </div>
        <div class="form-group">
          <label for="email">Електронна пошта:</label>
          <input type="email" v-model="email" required>
        </div>
        <div class="form-group">
          <label for="payment">Спосіб оплати:</label>
          <select v-model="payment" required>
            <option value="card">Картка</option>
            <option value="hotel">Оплата в готелі</option>
          </select>
        </div>
        <button type="submit">Завершити реєстрацію</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppRegistration',
  data() {
    return {
      name: '',
      surname: '',
      phone: '',
      email: '',
      payment: 'card'
    };
  },
  methods: {
    register() {
      alert('Реєстрація успішна!');
      this.$router.push('/user');
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap');

.registration-wrapper {
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

.registration-form {
  width: 100%;
  max-width: 600px;
  padding: 20px;
  margin: 0 20px; 
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  font-family: 'Gabriela', serif;
  box-sizing: border-box;
}

.registration-form h2 {
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

.form-group input,
.form-group select {
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

@media (max-width: 768px) {
  .registration-form {
    padding: 15px;
  }

  button {
    font-size: 14px;
    padding: 8px 15px;
  }
}

@media (max-width: 480px) {
  .registration-form {
    padding: 10px;
  }

  button {
    font-size: 12px;
    padding: 8px 10px;
  }
}
</style>


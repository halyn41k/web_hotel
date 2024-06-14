<template>
  <div class="registration-wrapper">
    <div id="background-image"></div>
    <div class="registration-form">
      <h2>Реєстрація</h2>
      <form @submit.prevent="register">
        <div class="form-group">
          <label for="name">Ім'я:</label>
          <input type="text" v-model="name" @input="validateName" required>
          <span v-if="nameError" class="error-message" v-show="nameErrorVisible">{{ nameError }}</span>
        </div>
        <div class="form-group">
          <label for="surname">Прізвище:</label>
          <input type="text" v-model="surname" @input="validateSurname" required>
          <span v-if="surnameError" class="error-message" v-show="surnameErrorVisible">{{ surnameError }}</span>
        </div>
        <div class="form-group">
          <label for="phone">Телефон:</label>
          <input type="tel" v-model="phone" @input="validatePhone" required>
          <span v-if="phoneError" class="error-message" v-show="phoneErrorVisible">{{ phoneError }}</span>
        </div>
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
        <div class="form-group">
          <label for="payment">Спосіб оплати:</label>
          <select v-model="payment" required>
            <option value="card">Картка</option>
            <option value="hotel">Оплата в готелі</option>
          </select>
        </div>
        <button type="submit" id="submit">Завершити реєстрацію</button>
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
      password: '',
      payment: 'card',
      nameError: '',
      surnameError: '',
      phoneError: '',
      emailError: '',
      passwordError: ''
    };
  },
  computed: {
    nameErrorVisible() {
      return !!this.nameError;
    },
    surnameErrorVisible() {
      return !!this.surnameError;
    },
    phoneErrorVisible() {
      return !!this.phoneError;
    },
    emailErrorVisible() {
      return !!this.emailError;
    },
    passwordErrorVisible() {
      return !!this.passwordError;
    }
  },
  methods: {
    async register() {
      if (!this.validateName()) return;
      if (!this.validateSurname()) return;
      if (!this.validatePhone()) return;
      if (!this.validateEmail()) return;
      if (!this.validatePassword()) return;

      try {
        const response = await fetch('http://localhost/new-hotel-website/register.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            name: this.name,
            surname: this.surname,
            phone: this.phone,
            email: this.email,
            password: this.password,
            payment: this.payment
          })
        });
        const data = await response.json();
        if (data.success) {
          localStorage.setItem('userData', JSON.stringify({
            name: this.name,
            surname: this.surname,
            email: this.email
          }));
          this.$router.push({ name: 'UserDashboard' });
        } else {
          alert(data.error);
        }
      } catch (error) {
        alert('Помилка при реєстрації');
      }
    },
    validateName() {
      const nameRegex = /^[A-ZА-ЯҐЄІЇ][a-zа-яґєії']*$/;
      if (!this.name.trim()) {
        this.nameError = "Ім'я є обов'язковим";
        return false;
      } else if (!nameRegex.test(this.name.trim())) {
        this.nameError = "Ім'я повинно починатися з великої літери та містити тільки букви";
        return false;
      } else {
        this.nameError = '';
        return true;
      }
    },
    validateSurname() {
      const surnameRegex = /^[A-ZА-ЯҐЄІЇ][a-zа-яґєії']*$/;
      if (!this.surname.trim()) {
        this.surnameError = "Прізвище є обов'язковим";
        return false;
      } else if (!surnameRegex.test(this.surname.trim())) {
        this.surnameError = "Прізвище повинно починатися з великої літери та містити тільки букви";
        return false;
      } else {
        this.surnameError = '';
        return true;
      }
    },
    validatePhone() {
      const phoneRegex = /^\d{10,}$/;
      if (!this.phone.trim()) {
        this.phoneError = 'Телефон є обов\'язковим';
        return false;
      } else if (!phoneRegex.test(this.phone.trim())) {
        this.phoneError = 'Телефон повинен містити мінімум 10 цифр';
        return false;
      } else {
        this.phoneError = '';
        return true;
      }
    },
    validateEmail() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!this.email.trim()) {
        this.emailError = 'Електронна пошта є обов\'язковою';
        return false;
      } else if (!emailRegex.test(this.email.trim())){
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
    document.title = 'Amethyst Hotel | Registration';
  }
};
</script>

<style scoped>
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
  margin: 60px 40px; 
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

.error-message {
  color: red;
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

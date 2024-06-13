<template>
    <div class="payment-wrapper">
      <div class="payment-form">
        <h2>Оплата</h2>
        
        <div class="payment-method">
          <h3>Спосіб оплати</h3>
          <label>
            <input type="radio" v-model="selectedPaymentMethod" value="cash">
            Оплата в готелі
          </label>
          <label>
            <input type="radio" v-model="selectedPaymentMethod" value="card">
            Оплата карткою
          </label>
        </div>
  
        <div v-if="selectedPaymentMethod === 'card'" class="card-details">
          <h3>Дані картки</h3>
          <div class="form-group">
            <label for="cardNumber">Номер картки:</label>
            <input type="text" v-model="cardNumber" @input="formatCardNumber" maxlength="19" required>
            <p v-if="!isValidCardNumber" class="error-message">Будь ласка, введіть коректний номер картки.</p>
          </div>
          <div class="form-group">
            <label for="expiryDate">Рік дії до:</label>
            <input type="text" v-model="expiryDate" @input="formatExpiryDate" required>
            <p v-if="!isValidExpiryDate" class="error-message">Будь ласка, введіть коректний термін дії картки (наприклад, 06/24).</p>
          </div>
          <div class="form-group">
            <label for="cvv">CVV:</label>
            <input type="text" v-model="cvv" @input="validateCvv" required>
            <p v-if="!isValidCvv" class="error-message">Будь ласка, введіть коректний CVV (три цифри).</p>
          </div>
        </div>
  
        <div class="payment-summary">
          <h3>Підсумок</h3>
          <p>Загальна вартість: {{ totalCost }} грн</p>
        </div>
  
        <button @click="processPayment">Оплатити</button>
  
        <div v-if="paymentSuccessful" class="success-message">
          Оплата здійснена успішно!
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'Payment',
    data() {
      return {
        selectedPaymentMethod: 'cash', // 'cash' or 'card'
        cardNumber: '',
        expiryDate: '',
        cvv: '',
        totalCost: 0, // Placeholder for total cost, should be computed or passed as prop
        paymentSuccessful: false,
        isValidCardNumber: true,
        isValidExpiryDate: true,
        isValidCvv: true,
        userId: 1,  // Приклад ID користувача, який виконав вхід або зареєструвався
        roomId: 1   // Приклад ID кімнати, яку користувач бажає забронювати
      };
    },
    methods: {
      formatCardNumber() {
        // Видалити всі символи, крім цифр
        this.cardNumber = this.cardNumber.replace(/[^\d]/g, '');
  
        // Обмежити до 16 символів
        this.cardNumber = this.cardNumber.substr(0, 16);
  
        // Додати пробіли через кожні 4 цифри
        this.cardNumber = this.cardNumber.replace(/(.{4})/g, '$1 ').trim();
  
        // Перевірити, чи є номер картки валідним (16 цифр)
        this.isValidCardNumber = /^\d{16}$/.test(this.cardNumber.replace(/\s/g, ''));
      },
      formatExpiryDate() {
        // Видалити всі символи, крім цифр та /
        this.expiryDate = this.expiryDate.replace(/[^\d/]/g, '');
  
        // Перевірити, чи є термін дії картки валідним (формат MM/YY та не менше 06/24)
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear() % 100;
        const currentMonth = currentDate.getMonth() + 1;
        const [inputMonth, inputYear] = this.expiryDate.split('/').map(item => parseInt(item, 10));
  
        this.isValidExpiryDate = (
          /^(0[1-9]|1[0-2])\/\d{2}$/.test(this.expiryDate) &&
          (inputYear > currentYear || (inputYear === currentYear && inputMonth >= currentMonth))
        );
      },
      validateCvv() {
        // Видалити всі символи, крім цифр
        this.cvv = this.cvv.replace(/\D/g, '');
  
        // Перевірити, чи є CVV валідним (три цифри)
        this.isValidCvv = /^\d{3}$/.test(this.cvv);
      },
      async processPayment() {
        try {
          const response = await axios.post('http://localhost/path/to/your/backend/payment.php', {
            selectedPaymentMethod: this.selectedPaymentMethod,
            cardNumber: this.cardNumber,
            expiryDate: this.expiryDate,
            cvv: this.cvv,
            totalCost: this.totalCost,
            userId: this.userId,
            roomId: this.roomId
          });
  
          if (response.data.success) {
            this.paymentSuccessful = true;
          } else {
            console.error('Помилка при обробці оплати:', response.data.error);
          }
        } catch (error) {
          console.error('Помилка при обробці оплати:', error);
        }
      }
    }
  };
  </script>
  

<style scoped>
.payment-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-image: url('/src/assets/Знімок екрана 2024-06-13 113316.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  padding: 10px;
}

.payment-form {
  width: 100%;
  max-width: 600px; /* Зменшимо максимальну ширину до 600px */
  margin: 50px auto;
  padding: 20px;
  background: rgba(255, 255, 255, 0.9); /* Прозорість білого кольору */
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 20px; /* Заокруглені кути */
  font-family: 'Gabriela', serif;
}

.payment-form h2 {
  margin-bottom: 20px;
  color: #6a0dad;
  text-align: center;
}

.payment-method {
  margin-bottom: 20px;
}

.payment-method h3 {
  margin-bottom: 10px;
  color: #6a0dad;
}

.payment-method label {
  display: block;
  margin-bottom: 10px;
}

.card-details {
  margin-bottom: 20px;
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
  width: calc(100% - 20px); /* Зменшимо ширину вводу на 20px менше, щоб уникнути виходження за межі */
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
}

.payment-summary {
  margin-bottom: 20px;
}

.payment-summary h3 {
  margin-bottom: 10px;
  color: #6a0dad;
}

.success-message {
  background-color: #4caf50;
  color: #fff;
  text-align: center;
  padding: 10px;
  border-radius: 5px;
  margin-top: 20px;
}

.error-message {
  color: red;
  margin-top: 5px;
}

button {
  background-color: #6a0dad;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  font-family: 'Gabriela', serif;
  transition: background-color 0.3s;
}

button:hover {
  background-color: #4a0b85;
}
</style>

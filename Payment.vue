<template>
  <div class="payment-wrapper">
    <div class="payment-form">
      <h2>Оплата</h2>
      <div>
        <p>Ім'я кімнати: {{ roomName }}</p>
        <p>Загальна вартість: {{ price }} грн</p>
        <div class="payment-method">
          <label for="paymentMethod">Спосіб оплати</label>
          <div class="form-group">
            <label for="card">Оплата карткою</label>
            <input type="radio" id="card" value="card" v-model="selectedPaymentMethod">
            <label for="cash">Оплата в готелі</label>
            <input type="radio" id="cash" value="cash" v-model="selectedPaymentMethod">
          </div>
        </div>
        <div v-if="selectedPaymentMethod === 'card'" class="card-details">
          <div class="form-group">
            <label for="cardNumber">Номер картки:</label>
            <input type="text" id="cardNumber" v-model="cardNumber" @input="formatCardNumber" required>
            <p v-if="!isValidCardNumber" class="error-message">Будь ласка, введіть коректний номер картки (16 цифр).</p>
          </div>
          <div class="form-group">
            <label for="expiryDate">Рік дії до:</label>
            <input type="text" id="expiryDate" v-model="expiryDate" @input="formatExpiryDate" maxlength="5" placeholder="MM/YY" required>
            <p v-if="!isValidExpiryDate" class="error-message">Будь ласка, введіть коректний термін дії картки (наприклад, 06/24).</p>
          </div>
          <div class="form-group">
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" v-model="cvv" @input="validateCvv" maxlength="3" required>
            <p v-if="!isValidCvv" class="error-message">Будь ласка, введіть коректний CVV (три цифри).</p>
          </div>
        </div>
        <button @click="processPayment">Оплатити</button>
        <div v-if="paymentSuccessful" class="success-message">
          Оплата здійснена успішно!
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Payment',
  data() {
    return {
      selectedPaymentMethod: 'cash',
      cardNumber: '',
      expiryDate: '',
      cvv: '',
      roomName: '',
      price: 0,
      bookingId: null,
      paymentSuccessful: false,
      isValidCardNumber: true,
      isValidExpiryDate: true,
      isValidCvv: true
    };
  },
  methods: {
    formatCardNumber() {
      this.cardNumber = this.cardNumber.replace(/[^\d]/g, '').substr(0, 16).replace(/(.{4})/g, '$1 ').trim();
      this.isValidCardNumber = /^\d{16}$/.test(this.cardNumber.replace(/\s/g, ''));
    },
    formatExpiryDate() {
      this.expiryDate = this.expiryDate.replace(/[^\d/]/g, '').substr(0, 5);
      if (/^\d{2}$/.test(this.expiryDate) && this.expiryDate.indexOf('/') === -1) {
        this.expiryDate = this.expiryDate.substr(0, 2) + '/';
      }
      this.isValidExpiryDate = /^(0[1-9]|1[0-2])\/\d{2}$/.test(this.expiryDate);
    },
    validateCvv() {
      this.cvv = this.cvv.replace(/\D/g, '');
      this.cvv = this.cvv.substr(0, 3);
      this.isValidCvv = /^\d{3}$/.test(this.cvv);
    },
    async processPayment() {
      try {
        const response = await fetch('http://localhost/new-hotel-website/payment.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            selectedPaymentMethod: this.selectedPaymentMethod,
            cardNumber: this.cardNumber,
            expiryDate: this.expiryDate,
            cvv: this.cvv,
            totalCost: this.price,
            bookingId: this.bookingId
          })
        });
        const data = await response.json();
        if (data.success) {
          this.paymentSuccessful = true;
          if (this.selectedPaymentMethod === 'cash' || this.isValidCardNumber && this.isValidExpiryDate && this.isValidCvv) {
            alert('Оплата здійснена успішно!');
            window.location.href = '/user'; // Перенаправлення на /user після успішної оплати
          }
          const userData = JSON.parse(localStorage.getItem('userData'));
          if (userData) {
            userData.bookings = userData.bookings.map(booking => {
              if (booking.id === this.bookingId) {
                booking.paid = true;
              }
              return booking;
            });
            localStorage.setItem('userData', JSON.stringify(userData));
          }
        } else {
          console.error('Помилка при обробці оплати:', data.error);
        }
      } catch (error) {
        console.error('Помилка при обробці оплати:', error);
      }
    }
  },
  mounted() {
    const bookingData = JSON.parse(localStorage.getItem('bookingData'));
    if (bookingData) {
      this.roomName = bookingData.room_name;
      this.price = bookingData.price;
      this.bookingId = bookingData.id;
    }
    document.title = 'Amethyst Hotel | Payment';
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
  max-width: 600px;
  margin: 50px auto;
  padding: 20px;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 20px;
  font-family: 'Gabriela', serif;
}

.payment-form h2 {
  margin-bottom: 20px;
  text-align: center;
}

.payment-form .form-group {
  margin-bottom: 20px;
}

.payment-form label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.payment-form input[type="text"] {
  width: calc(100% - 22px);
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

.payment-form .payment-method {
  margin-bottom: 20px;
}

.payment-form .payment-method input[type="radio"] {
  margin-right: 10px;
}

.payment-form .error-message {
  color: red;
  font-size: 14px;
}

.payment-form button {
  width: 100%;
  padding: 15px;
  background-color: #e67e22;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 18px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
  transition: background-color 0.3s ease;
}

.payment-form button:hover {
  background-color: #d35400;
}

.payment-form .success-message {
  margin-top: 20px;
  color: green;
  font-size: 16px;
  text-align: center;
}
</style>

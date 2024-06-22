<template>
  <div class="booking-form-wrapper">
    <div class="booking-form" :class="{ narrow: step === 1 }">
      <h2>Забронювати кімнату</h2>
      <div v-if="step === 1">
        <div class="form-group">
          <label for="checkin">Дата заїзду:</label>
          <input type="date" v-model="checkin" :min="minDate" required>
        </div>
        <div class="form-group">
          <label for="checkout">Дата виїзду:</label>
          <input type="date" v-model="checkout" :min="checkin" required>
        </div>
        <div class="form-group">
          <label for="adults">Дорослі:</label>
          <input type="number" v-model="adults" min="1" required>
        </div>
        <div class="form-group">
          <label for="children">Діти:</label>
          <input type="number" v-model="children" min="0" required>
        </div>
        <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
        <button @click="checkAvailability">Перевірити наявність</button>
      </div>
      <div v-else-if="step === 2">
        <h3>Доступні кімнати</h3>
        <div class="rooms-grid" :class="{ center: availableRooms.length === 1 }">
          <div v-for="room in availableRooms" :key="room.id" class="room-option">
            <img :src="`http://localhost/new-hotel-website/src/assets/${room.photo}`" class="room-photo" :alt="room.name" />
            <div class="room-details">
              <h4>{{ room.name }}</h4>
              <p>Ціна: {{ room.price }} грн/ніч</p>
              <p>Доступність: {{ room.availability }}</p>
              <p>Тип ліжка: {{ room.bedType }}</p>
              <button @click="selectRoom(room)">Обрати номер</button>
            </div>
          </div>
        </div>
      </div>
      <div v-else-if="step === 3">
        <h3>Завершити бронювання?</h3>
        <div class="button-group">
          <button @click="goBack" class="back-button">Повернутися назад</button>
          <button @click="completeBooking" class="complete-booking">Завершити бронювання</button>
        </div>
      </div>
      <div v-else-if="step === 4">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BookingForm',
  data() {
    return {
      step: 1,
      checkin: '',
      checkout: '',
      adults: 1,
      children: 0,
      availableRooms: [],
      errorMessage: '',
      minDate: new Date().toISOString().split('T')[0],
      selectedRoom: null
    };
  },
  computed: {
    userHasBooking() {
      const userData = JSON.parse(localStorage.getItem('userData'));
      return userData && userData.bookings && userData.bookings.length > 0;
    }
  },
  created() {
    if (this.$route.query.room) {
      this.selectedRoom = JSON.parse(this.$route.query.room);
      this.availableRooms = [this.selectedRoom];
      this.step = 2;
    }
  },
  methods: {
  async checkAvailability() {
    if (!this.checkin || !this.checkout || this.adults < 1) {
      this.errorMessage = 'Будь ласка, заповніть всі поля форми коректно.';
      return;
    }
    this.errorMessage = '';

    try {
      const response = await fetch('http://localhost/new-hotel-website/backend/booking.php', {
        method: 'GET',
      });
      const data = await response.json();
      if (data.success) {
        this.availableRooms = data.rooms;
        this.step = 2;
      } else {
        this.errorMessage = 'Не вдалося отримати дані про наявні кімнати.';
      }
    } catch (error) {
      console.error('Помилка отримання даних:', error);
      this.errorMessage = 'Сталася помилка під час отримання даних.';
    }
  },
  async selectRoom(room) {
    const userData = JSON.parse(localStorage.getItem('userData'));
    console.log('User Data:', userData); // Debugging

    if (!userData) {
      this.$router.push('/registration');
      return;
    }

    if (this.userHasBooking) {
      this.errorMessage = 'У вас вже є бронювання. Ви не можете зробити нове бронювання.';
      return;
    }

    const bookingData = {
      checkin: this.checkin,
      checkout: this.checkout,
      adults: this.adults,
      children: this.children,
      user_id: userData.id,
      room_id: room.id,
      room_name: room.name,
      price: room.price
    };

    console.log('Booking Data:', bookingData); // Debugging

    try {
      const response = await fetch('http://localhost/new-hotel-website/backend/booking.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(bookingData)
      });
      const data = await response.json();
      console.log('Response Data:', data); // Debugging

      if (data.success) {
        // Оновлення бронювань користувача в localStorage
        userData.bookings = userData.bookings || [];
        const newBooking = {
          id: data.booking_id,
          checkin: this.checkin,
          checkout: this.checkout,
          room: room.name,
          price: room.price,
          paid: false
        };
        userData.bookings.push(newBooking);
        localStorage.setItem('userData', JSON.stringify(userData));
        localStorage.setItem('bookingData', JSON.stringify(bookingData));
        
        this.step = 3; // Після успішного бронювання переходимо на етап завершення бронювання
      } else {
        this.errorMessage = data.error;
      }
    } catch (error) {
      console.error('Помилка бронювання:', error);
      this.errorMessage = 'Сталася помилка під час бронювання.';
    }
  },
  goBack() {
    this.step = 2; // Повертаємося назад до вибору номеру після натискання "Повернутися назад"
  },
  completeBooking() {
    this.step = 4; // Переходимо на етап оплати
    this.$router.push('/payment');
  }
}
};
</script>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');

.booking-form-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-image: url('@/assets/Знімок екрана 2024-05-31 152043.png');
  background-size: cover;
  background-position: center;
  padding: 10px;
}

.booking-form {
  max-width: 800px; /* Adjust this value as needed */
  width: 100%;
  margin: 50px auto;
  padding: 20px;
  background: rgba(255, 255, 255, 0.8);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  font-family: 'Gabriela', serif;
}

.booking-form.narrow {
  max-width: 500px; /* Adjust this value as needed */
}

.booking-form h2 {
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
  width: calc(100% - 20px); /* Make the input fields wider */
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
  margin-right: 10px; /* Add space to the right side */
}

button {
  background-color: #e67e22;
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
  background-color: #d35400;
}

.room-option {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background: #fff;
}

.room-photo {
  width: 150px;
  height: 150px;
  object-fit: cover;
  margin-right: 15px;
  border-radius: 5px;
}

.room-details {
  flex: 1;
}

.room-details h4 {
  margin-bottom: 10px;
}

.room-details p {
  margin: 5px 0;
}

.rooms-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.rooms-grid.center {
  grid-template-columns: 1fr;
  justify-items: center;
}

.complete-booking, .back-button {
  display: block;
  text-align: center;
  padding: 10px 20px;
  border-radius: 5px;
  margin: 10px 0;
}

.complete-booking {
  background-color: #e67e22;
  color: white;
  border: none;
  cursor: pointer;
}

.back-button {
  background-color: #d3d3d3;
  color: black;
  border: none;
  cursor: pointer;
}

.error-message {
  color: red;
  margin-top: 10px;
}
</style>

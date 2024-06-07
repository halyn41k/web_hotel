<template>
  <div class="booking-form-wrapper">
    <div class="booking-form">
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
        <div v-for="room in availableRooms" :key="room.id" class="room-option">
          <h4>{{ room.name }}</h4>
          <p>Ціна: {{ room.price }} грн/ніч</p>
          <p>Доступно: {{ room.available }} кімнат</p>
          <p>Ліжок: {{ room.beds }}</p>
          <button @click="selectRoom(room)">Обрати номер</button>
        </div>
      </div>
      <div v-else-if="step === 3">
        <h3>Завершити бронювання?</h3>
        <div class="button-group">
          <button @click="goBack" class="back-button">Повернутися назад</button>
          <router-link to="/registration" class="complete-booking">Завершити бронювання</router-link>
        </div>
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
      minDate: new Date().toISOString().split('T')[0]
    };
  },
  methods: {
    checkAvailability() {
      if (!this.checkin || !this.checkout || this.adults < 1) {
        this.errorMessage = 'Будь ласка, заповніть всі поля форми коректно.';
        return;
      }
      this.errorMessage = '';
      this.availableRooms = [
        {
          id: 1,
          name: 'Люкс "Сонячний"',
          photo: require('@/assets/729a4fa082a5edc17eab279dd41c87f7.jpg'),
          description: "Просторий люкс з чудовим видом на сонце.",
          price: 2500,
          beds: 2,
          available: 2
        },
        {
          id: 2,
          name: 'Номер "Мегаполіс Мрій"',
          photo: require('@/assets/102816Yotel03.webp'),
          description: "Затишний номер з видом на хмарочоси.",
          price: 3500,
          beds: 2,
          available: 0
        },
        {
          id: 3,
          name: 'Люкс "Оаза Затишку"',
          photo: require('@/assets/9954929b8c5f8e5fef03f9acb136498e.jpg'),
          description: "Елегантний люкс з власним душем та телевізором.",
          price: 3000,
          beds: 2,
          available: 1
        },
        {
          id: 4,
          name: 'Номер "Романтичний вечір"',
          photo: require('@/assets/399085717.jpg'),
          description: "Атмосферний номер для романтичного вечора.",
          price: 1800,
          beds: 2,
          available: 3
        },
        {
          id: 5,
          name: 'Люкс "Роскішна діяльність"',
          photo: require('@/assets/cl_xs1.jpg'),
          description: "Сучасний інтер'єр у поєднанні з вишуканим комфортом.",
          price: 4000,
          beds: 2,
          available: 2
        },
        {
          id: 6,
          name: 'Номер "Комфортне життя"',
          photo: require('@/assets/desc_0SUAupCbJIoVoq.jpg'),
          description: "Номер для сім'ї для комфортного проживання.",
          price: 5000,
          beds: 4,
          available: 1
        },
        {
          id: 7,
          name: 'Люкс "Фіолетова абстракція"',
          photo: require('@/assets/hotel-purple (1).jpg'),
          description: "Ексклюзивний номер з вражаючим інтер’єром у стилі абстракції.",
          price: 1000,
          beds: 2,
          available: 3
        }
      ];
      this.step = 2;
    },
    selectRoom(room) {
      localStorage.setItem('selectedRoom', JSON.stringify(room));
      this.step = 3;
    },
    goBack() {
      this.step = 2;
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');

*,
*::before,
*::after {
  box-sizing: border-box;
}

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
  max-width: 600px;
  width: 100%;
  margin: 50px auto;
  padding: 20px;
  background: rgba(255, 255, 255, 0.8);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  font-family: 'Gabriela', serif;
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
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
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
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-bottom: 10px;
}

.room-option h4 {
  margin-bottom: 10px;
}

.room-option p {
  margin: 5px 0;
}

.complete-booking, .back-button {
  display: block;
  text-align: center;
  padding: 10px 20px;
  border-radius: 5px;
  text-decoration: none;
  font-size: 16px;
  font-family: 'Gabriela', serif;
}

.complete-booking {
  background-color: #e67e22;
  color: white;
}

.complete-booking:hover {
  background-color: #d35400;
}

.back-button {
  background-color: #6a0dad;
  color: white;
}

.back-button:hover {
  background-color: #4a0b85;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.error-message {
  color: red;
  font-size: 14px;
  margin-bottom: 15px;
}

/* Media Queries for Responsive Design */

@media (max-width: 768px) {
  .booking-form {
    padding: 15px;
    margin: 30px auto;
  }

  button, .complete-booking, .back-button {
    font-size: 14px;
    padding: 8px 15px;
  }

  .room-option {
    padding: 10px;
  }

  .button-group {
    flex-direction: column;
  }

  .button-group .back-button,
  .button-group .complete-booking {
    margin-bottom: 10px;
    width: 100%;
  }
}

@media (max-width: 480px) {
  .booking-form {
    padding: 10px;
    margin: 20px auto;
  }

  button, .complete-booking, .back-button {
    font-size: 12px;
    padding: 8px 10px;
  }

  .room-option {
    padding: 8px;
  }

  .button-group {
    flex-direction: column;
  }

  .button-group .back-button,
  .button-group .complete-booking {
    margin-bottom: 8px;
    width: 100%;
  }
}
</style>


<template>
  <div class="dashboard-wrapper">
    <div id="background-image"></div>
    <div class="dashboard-content">
      <div class="profile-section">
        <div class="user-info">
          <h2>{{ name }} {{ surname }}</h2>
          <p>Email: {{ email }}</p>
          <p>Номер телефону: {{ phone }}</p>
        </div>
      </div>
      <div class="bookings-section">
        <h3>Ваші бронювання:</h3>
        <ul v-if="bookings.length > 0">
          <li v-for="(booking, index) in bookings" :key="index">
            <p><strong>Дата заїзду:</strong> {{ booking.checkin }}</p>
            <p><strong>Дата виїзду:</strong> {{ booking.checkout }}</p>
            <p><strong>Номер кімнати:</strong> {{ booking.room_name }}</p>
            <p><strong>Ціна:</strong> {{ booking.room_price }} грн/ніч</p>
            <p><strong>Статус:</strong> {{ booking.paid ? 'Оплачено' : 'Не оплачено' }}</p>
            <button v-if="!booking.paid" class="pay-booking-button" @click="payBooking(booking)">Оплатити</button>
            <button class="delete-booking-button" @click="deleteBooking(index, booking.id)">Видалити бронювання</button>
          </li>
        </ul>
        <p v-else>У вас немає бронювань.</p>
        <button v-if="bookings.length === 0" class="book-room-button" @click="bookRoom">Забронювати кімнату</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserDashboard',
  data() {
    return {
      name: '',
      surname: '',
      email: '',
      phone: '',
      bookings: [],
      userId: ''
    };
  },
  methods: {
    bookRoom() {
      this.$router.push('/booking');
    },
    async payBooking(booking) {
      try {
        const payload = {
          bookingId: booking.id,
          roomId: booking.room_id
        };

        const response = await fetch('http://localhost/new-hotel-website/payment.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const data = await response.json();
        if (data.success) {
          this.$router.push(`/payment?bookingId=${booking.id}&roomName=${booking.room_name}&roomPrice=${booking.room_price}`);
        } else {
          console.error(data.error);
        }
      } catch (error) {
        console.error('Помилка оплати бронювання:', error);
      }
    },
    async deleteBooking(index, bookingId) {
      try {
        const response = await fetch('http://localhost/new-hotel-website/booking.php', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: bookingId })
        });
        const data = await response.json();
        if (data.success) {
          this.bookings.splice(index, 1);
        } else {
          console.error(data.error);
        }
      } catch (error) {
        console.error('Помилка видалення бронювання:', error);
      }
    },
    async fetchBookings() {
      const userData = JSON.parse(localStorage.getItem('userData'));
      if (!userData) {
        this.$router.push('/registration');
        return;
      }

      try {
        const response = await fetch(`http://localhost/new-hotel-website/booking.php?user_id=${userData.id}`, {
          method: 'GET'
        });
        const data = await response.json();
        if (data.success) {
          this.bookings = data.bookings.map(booking => ({
            checkin: booking.checkin,
            checkout: booking.checkout,
            room_name: booking.room_name,
            room_price: booking.room_price,
            paid: booking.paid,
            id: booking.id,
            room_id: booking.room_id
          }));
        } else {
          console.error(data.error);
        }
      } catch (error) {
        console.error('Помилка завантаження бронювань:', error.response || error.message || error);
      }
    },
    fetchUserData() {
      const userData = JSON.parse(localStorage.getItem('userData'));
      if (!userData) {
        this.$router.push('/registration');
        return;
      }

      this.name = userData.name;
      this.surname = userData.surname;
      this.email = userData.email;
      this.phone = userData.phone;
      this.userId = userData.id;
    }
  },
  created() {
    this.fetchUserData();
    this.fetchBookings();
  },
  mounted() {
    document.title = 'Amethyst Hotel | User';
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');

.dashboard-wrapper {
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

.dashboard-content {
  max-width: 800px;
  margin: 150px auto 0 auto;
  padding: 20px;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  font-family: 'Gabriela', serif;
  position: relative;
  z-index: 1;
}

.profile-section {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.user-info {
  flex: 1;
}

.user-info h2 {
  font-family: 'Gabriela', serif;
  margin-bottom: 10px;
  color: #6a0dad;
}

.user-info p {
  font-family: 'Gabriela', serif; /* Change this to your desired classic font */
  color: #333;
}

.bookings-section {
  margin-top: 10px;
  text-align: center;
}

.bookings-section h3 {
  font-family: 'Gabriela', serif;
  color: #6a0dad;
  margin-bottom: 1px;
}

.bookings-section ul {
  list-style-type: none;
  padding: 0;
}

.bookings-section li {
  background: #f8f8f8;
  margin-bottom: 15px;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 5px;
  font-family: 'Gabriela', serif; /* Change this to your desired classic font */
  position: relative;
}

.pay-booking-button {
  padding:   5px 10px;
  background-color: #28a745; /* Green color */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
  margin: 10px auto 0; /* Центруємо кнопку по горизонталі з верхнім відступом 10px */
  display: block; /* Зробити кнопку блоковим елементом для коректного вирівнювання */
}

.delete-booking-button {
  padding: 5px 10px;
  background-color: #ff6347;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
  display: block; /* Зробити кнопку блоковим елементом для коректного вирівнювання */
  margin: 10px auto; /* Центруємо кнопку по горизонталі з верхнім і нижнім відступами по 10px */
}

.book-room-button {
  padding: 15px 30px;
  background-color: #6a0dad;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
  font-size: 18px;
  transition: background-color 0.3s;
  margin-top: 20px;
  display: block; /* Зробити кнопку блоковим елементом для коректного вирівнювання */
  margin-left: auto;
  margin-right: auto;
}

.book-room-button:hover {
  background-color: #6a0dad;
}

@media screen and (max-width: 768px) {
  .dashboard-content {
    padding: 10px;
  }
  
  .profile-section {
    flex-direction: column;
    margin-bottom: 10px;
  }
  
  .user-info {
    text-align: center;
    margin-top: 10px;
  }
  
  .book-room-button,
  .delete-booking-button {
    margin-top: 20px;
  }
}
</style>


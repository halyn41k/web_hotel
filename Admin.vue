<template>
    <div class="admin-dashboard-wrapper">
      <div id="background-image"></div>
      <div class="admin-dashboard-content">
        <div class="admin-profile-section">
          <div class="admin-user-info">
            <h2>Адміністратор</h2>
            <p>Email: admin@gmail.com</p>
          </div>
        </div>
        <div class="admin-bookings-section">
          <h3>Активні Бронювання:</h3>
          <ul v-if="bookings.length > 0">
            <li v-for="(booking, index) in bookings" :key="index">
              <p><strong>ID бронювання:</strong> {{ booking.id }}</p>
              <p><strong>Дата заїзду:</strong> {{ booking.checkin }}</p>
              <p><strong>Дата виїзду:</strong> {{ booking.checkout }}</p>
              <p><strong>Номер кімнати:</strong> {{ booking.room_id }}</p>
              <p><strong>Користувач:</strong> {{ booking.user_id }}</p>
            </li>
          </ul>
          <p v-else>Немає активних бронювань.</p>
        </div>
        <div class="admin-wishes-section">
          <h3>Побажання Користувачів:</h3>
          <ul v-if="wishes.length > 0">
            <li v-for="(wish, index) in wishes" :key="index">
              <p><strong>ID побажання:</strong> {{ wish.id }}</p>
              <p><strong>Користувач:</strong> {{ wish.user_id }}</p>
              <p><strong>Побажання:</strong> {{ wish.wish }}</p>
            </li>
          </ul>
          <p v-else>Немає побажань користувачів.</p>
        </div>
        <p v-if="error" class="error">{{ error }}</p>
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { defineComponent } from 'vue';
  import axios from 'axios';
  
  interface Booking {
    id: number;
    checkin: string;
    checkout: string;
    room_id: number;
    user_id: number;
  }
  
  interface Wish {
    id: number;
    user_id: number;
    wish: string;
  }
  
  export default defineComponent({
    name: 'Admin',
    data() {
      return {
        bookings: [] as Booking[],
        wishes: [] as Wish[],
        error: null as string | null,
      };
    },
    created() {
      axios
        .get<{ error?: string; bookings: Booking[]; wishes: Wish[] }>('http://localhost/new-hotel-website/admin.php')
        .then(response => {
          if (response.data.error) {
            this.error = response.data.error;
          } else {
            this.bookings = response.data.bookings;
            this.wishes = response.data.wishes;
          }
        })
        .catch(error => {
          this.error = 'Помилка отримання даних.';
          console.error('Fetch data error:', error);
        });
    },
    mounted() {
    document.title = 'Amethyst Hotel | Admin';
  }
  });
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');
  
  .admin-dashboard-wrapper {
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
  
  .admin-dashboard-content {
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
  
  .admin-profile-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .admin-user-info {
    flex: 1;
    text-align: center;
  }
  
  .admin-user-info h2 {
    font-family: 'Gabriela', serif;
    margin-bottom: 10px;
    color: #6a0dad;
  }
  
  .admin-user-info p {
    font-family: 'Gabriela', serif;
    color: #333;
  }
  
  .admin-bookings-section,
  .admin-wishes-section {
    margin-top: 10px;
    text-align: center;
  }
  
  .admin-bookings-section h3,
  .admin-wishes-section h3 {
    font-family: 'Gabriela', serif;
    color: #6a0dad;
    margin-bottom: 10px;
  }
  
  .admin-bookings-section ul,
  .admin-wishes-section ul {
    list-style-type: none;
    padding: 0;
  }
  
  .admin-bookings-section li,
  .admin-wishes-section li {
    background: #f8f8f8;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    font-family: 'Gabriela', serif;
  }
  
  .error {
    color: red;
  }
  
  @media screen and (max-width: 768px) {
    .admin-dashboard-content {
      padding: 10px;
    }
  }
  </style>
  
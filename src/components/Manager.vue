<template>
    <div class="manager-dashboard-wrapper">
      <div id="background-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
      <div class="manager-dashboard-content">
        <div class="manager-profile-section">
          <div class="manager-user-info">
            <h2>{{ admin.name }} {{ admin.surname }}</h2>
            <p>Email: {{ admin.email }}</p>
            <p>Роль: {{ admin.role }}</p>
          </div>
        </div>
        <div class="admin-room-actions">
          <router-link to="/add-room">
            <button class="action-button">Додати Кімнату</button>
          </router-link>
          <router-link to="/delete-room">
            <button class="action-button">Видалити Кімнату</button>
          </router-link>
          <router-link to="/change-room">
            <button class="action-button">Змінити Кімнату</button>
          </router-link>
        </div>
        <div class="admin-bookings-section">
          <h3>Активні Бронювання:
            <span class="filter-label">Фільтр</span>
            <img :src="sortIcon" @click="toggleSortDropdown('bookings')" alt="Sort" class="sort-icon-common">
          </h3>
          <div v-if="showSortBookingsDropdown" class="sort-dropdown">
            <ul>
              <li @click="sortBookings('id')">ID бронювання</li>
              <li @click="sortBookings('checkin')">Дата заїзду</li>
              <li @click="sortBookings('checkout')">Дата виїзду</li>
              <li @click="sortBookings('room_id')">Номер кімнати</li>
              <li @click="sortBookings('user_id')">Користувач</li>
            </ul>
          </div>
          <table v-if="bookings.length > 0">
            <thead>
              <tr>
                <th>ID бронювання</th>
                <th>Дата заїзду</th>
                <th>Дата виїзду</th>
                <th>Назва кімнати</th>
                <th>Користувач</th>
                <th>Дія</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(booking, index) in bookings" :key="index">
                <td>{{ booking.id }}</td>
                <td>{{ booking.checkin }}</td>
                <td>{{ booking.checkout }}</td>
                <td>{{ booking.room_name }}</td>
                <td>{{ booking.user_name }} {{ booking.user_surname }}</td>
                <td>
                  <img :src="deleteIcon" @click="deleteBooking(booking.id)" alt="Delete" class="delete-icon">
                </td>
              </tr>
            </tbody>
          </table>
          <p v-else>Немає активних бронювань.</p>
        </div>
        <div class="admin-wishes-section">
          <h3>Побажання Користувачів:
            <span class="filter-label">Фільтр</span>
            <img :src="sortIcon" @click="toggleSortDropdown('wishes')" alt="Sort" class="sort-icon-common">
          </h3>
          <div v-if="showSortWishesDropdown" class="sort-dropdown">
            <ul>
              <li @click="sortWishes('id')">ID побажання</li>
              <li @click="sortWishes('name')">Ім'я</li>
              <li @click="sortWishes('email')">Електронна пошта</li>
              <li @click="sortWishes('message')">Побажання</li>
            </ul>
          </div>
          <table v-if="wishes.length > 0">
            <thead>
              <tr>
                <th>ID побажання</th>
                <th>Ім'я</th>
                <th>Електронна пошта</th>
                <th>Побажання</th>
                <th>Дія</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(wish, index) in wishes" :key="index">
                <td>{{ wish.id }}</td>
                <td>{{ wish.name }}</td>
                <td>{{ wish.email }}</td>
                <td>{{ wish.message }}</td>
                <td>
                  <img :src="deleteIcon" @click="deleteWish(wish.id)" alt="Delete" class="delete-icon">
                </td>
              </tr>
            </tbody>
          </table>
          <p v-else>Немає побажань користувачів.</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'AdminPanel',
    data() {
      return {
        admin: {
          name: '',
          surname: '',
          email: '',
          role: '',
        },
        bookings: [],
        wishes: [],
        error: null,
        showSortBookingsDropdown: false,
        showSortWishesDropdown: false,
        currentSortSection: '',
        backgroundImage: '',
        sortIcon: '',
        deleteIcon: ''
      };
    },
    created() {
      document.title = 'Amethyst Hotel | Admin';
      this.fetchData();
      this.fetchImages();
    },
    methods: {
      fetchData() {
        axios
          .get('http://localhost/new-hotel-website/backend/admin.php')
          .then((response) => {
            if (response.data.error) {
              this.error = response.data.error;
            } else {
              this.admin = response.data.admin;
              this.bookings = response.data.bookings;
              this.wishes = response.data.wishes;
            }
          })
          .catch((error) => {
            this.error = 'Помилка отримання даних.';
            console.error('Error fetching data:', error);
          });
      },
      fetchImages() {
        axios
          .get('http://localhost/new-hotel-website/backend/get_images.php')
          .then((response) => {
            const images = response.data;
            this.backgroundImage = this.findImage(images, 'login', 'login.png');
            this.sortIcon = this.findImage(images, 'admin', 'image-removebg-preview 213.png');
            this.deleteIcon = this.findImage(images, 'admin', 'image-removebg-preview (3).png');
          })
          .catch((error) => {
            console.error('Error fetching images:', error);
          });
      },
      findImage(images, category, imageName) {
        const image = images.find(img => img.category === category && img.image_name === imageName);
        return image ? `http://localhost/new-hotel-website/src/assets/${image.image_name}` : '';
      },
      deleteBooking(id) {
        axios
          .post('http://localhost/new-hotel-website/backend/delete_booking.php', { type: 'booking', id: id })
          .then((response) => {
            if (response.data.success) {
              this.fetchData();
            } else {
              this.error = response.data.error;
            }
          })
          .catch((error) => {
            this.error = 'Помилка видалення бронювання.';
            console.error('Error deleting booking:', error);
          });
      },
      deleteWish(id) {
        axios
          .post('http://localhost/new-hotel-website/backend/delete_booking.php', { type: 'wish', id: id })
          .then((response) => {
            if (response.data.success) {
              this.fetchData();
            } else {
              this.error = response.data.error;
            }
          })
          .catch((error) => {
            this.error = 'Помилка видалення побажання.';
            console.error('Error deleting wish:', error);
          });
      },
      toggleSortDropdown(section) {
        if (section === 'bookings') {
          this.showSortBookingsDropdown = !this.showSortBookingsDropdown;
          this.showSortWishesDropdown = false;
        } else if (section === 'wishes') {
          this.showSortWishesDropdown = !this.showSortWishesDropdown;
          this.showSortBookingsDropdown = false;
        }
        this.currentSortSection = section;
      },
      sortBookings(field) {
        axios
          .get(`http://localhost/new-hotel-website/backend/sort_bookings.php?field=${field}`)
          .then((response) => {
            if (response.data.error) {
              this.error = response.data.error;
            } else {
              this.bookings = response.data.bookings;
              this.showSortBookingsDropdown = false;
            }
          })
          .catch((error) => {
            this.error = 'Помилка сортування бронювань.';
            console.error('Error sorting bookings:', error);
          });
      },
      sortWishes(field) {
        axios
          .get(`http://localhost/new-hotel-website/backend/sort_wishes.php?field=${field}`)
          .then((response) => {
            if (response.data.error) {
              this.error = response.data.error;
            } else {
              this.wishes = response.data.wishes;
              this.showSortWishesDropdown = false;
            }
          })
          .catch((error) => {
            this.error = 'Помилка сортування побажань.';
            console.error('Error sorting wishes:', error);
          });
      }
    }
  };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');
  
  .admin-dashboard-wrapper {
    position: relative;
    max-width: 100%;
    min-height: 100vh;
    overflow-x: hidden;
    overflow-y: auto; /* Enable vertical scrolling */
  }
  
  #background-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    z-index: -1;
  }
  
  .admin-dashboard-content {
    max-width: 1200px; /* Increased max-width */
    margin: 150px auto 0 auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    font-family: 'Gabriela', serif;
    position: relative;
    z-index: 1;
    min-height: calc(100vh - 150px); /* Ensures the content box covers most of the screen height */
  }
  
  .admin-profile-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .admin-user-info {
    flex: 1;
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
    position: relative; /* Add relative positioning */
  }
  
  .filter-label {
    margin-right: 5px;
    font-size: 16px;
    vertical-align: middle;
    position: absolute; /* Use absolute positioning */
    right: 30px; /* Move 30px to the right */
  }
  
  .sort-dropdown {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 2;
    right: 0; /* Align dropdown to the right */
  }
  
  .sort-dropdown ul {
    list-style: none;
    padding: 10px;
    margin: 0;
    text-align: right; /* Align text to the right */
  }
  
  .sort-dropdown li {
    padding: 5px 10px;
    cursor: pointer;
  }
  
  .sort-dropdown li:hover {
    background-color: #f0f0f0;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-family: 'Gabriela', serif;
  }
  
  table th,
  table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
  }
  
  table th {
    background-color: #49146F;
    color: white;
    cursor: pointer; /* Add cursor pointer to indicate sortability */
  }
  
  p {
    margin: 5px 0;
  }
  
  p strong {
    font-weight: bold;
  }
  
  .sort-icon {
    cursor: pointer;
    width: 20px;
    height: 20px;
    position: absolute;
  }
  
  .sort-icon-common {
    cursor: pointer;
    width: 20px;
    height: 20px;
    position: absolute; /* Use absolute positioning */
    right: 10px; /* Move 10px to the right */
  }
  
  .sort-icon-common:hover {
    filter: invert(31%) sepia(91%) saturate(2813%) hue-rotate(262deg) brightness(78%) contrast(97%);
  }
  
  .delete-icon {
    cursor: pointer;
    width: 20px;
    height: 20px;
    position: relative;
  }
  
  .admin-room-actions {
    margin-top: 20px;
    text-align: center;
  }
  
  .action-button {
    border-radius: 10px;
    background-color: #6a0dad;
    color: white;
    border: none;
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    font-family: 'Gabriela', serif;
  }
  
  .action-button:hover {
    background-color: #49146F;
  }
  
  @media screen and (max-width: 768px) {
    .admin-dashboard-content {
      padding: 10px;
    }
  
    .admin-profile-section {
      flex-direction: column;
      margin-bottom: 10px;
    }
  
    .admin-user-info {
      text-align: center;
      margin-top: 10px;
    }
  
    .filter-label,
    .sort-icon-common {
      position: static; /* Reset position to default for mobile */
      margin-left: 0;
    }
    
    .sort-dropdown {
      right: 10px;
      left: auto;
      text-align: left;
    }
    
    .sort-dropdown ul {
      text-align: left;
    }
  
    table th,
    table td {
      padding: 4px;
    }
  
    table th {
      font-size: 14px;
    }
  
    table td {
      font-size: 12px;
    }
  }
  
  @media screen and (max-width: 480px) {
    .admin-dashboard-content {
      padding: 10px;
    }
  
    .admin-profile-section {
      flex-direction: column;
      margin-bottom: 10px;
    }
  
    .admin-user-info {
      text-align: center;
      margin-top: 10px;
    }
  
    .filter-label,
    .sort-icon-common {
      position: static; /* Reset position to default for mobile */
      margin-left: 0;
    }
    
    .sort-dropdown {
      right: 10px;
      left: auto;
      text-align: left;
    }
    
    .sort-dropdown ul {
      text-align: left;
    }
  
    table th,
    table td {
      padding: 4px;
    }
  
    table th {
      font-size: 12px;
    }
  
    table td {
      font-size: 10px;
    }
  }
  </style>
  
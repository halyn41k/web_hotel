<template>
  <div class="manager-dashboard-wrapper">
    <div id="background-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
    <div class="manager-dashboard-content">
      <div class="manager-profile-section">
        <div class="manager-user-info">
          <h2>{{ manager.name }} {{ manager.surname }}</h2>
          <p>Email: {{ manager.email }}</p>
          <p>Роль: {{ manager.role }}</p>
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
        <router-link to="/add-admin">
          <button class="action-button">Додати Адміністратора</button>
        </router-link>
        <router-link to="/delete-admin">
          <button class="action-button">Видалити Адміністратора</button>
        </router-link>
      </div>
      <div class="manager-bookings-section">
        <h3>Активні Бронювання:</h3>
        <span @click="toggleSortDropdown('bookings')" class="filter-label">
          <img :src="sortIcon" alt="Sort" class="sort-icon-common">
        </span>
        <div v-if="showSortBookingsDropdown" class="sort-dropdown">
          <ul>
            <li @click="sortBookings('checkin')">Дата заїзду</li>
            <li @click="sortBookings('checkout')">Дата виїзду</li>
            <li @click="sortBookings('room_name')">Назва кімнати</li>
            <li @click="sortBookings('user_name')">Користувач</li>
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
      <div class="manager-wishes-section">
        <h3>Побажання Користувачів:</h3>
        <span @click="toggleSortDropdown('wishes')" class="filter-label">
          <img :src="sortIcon" alt="Sort" class="sort-icon-common">
        </span>
        <div v-if="showSortWishesDropdown" class="sort-dropdown">
          <ul>
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
  name: 'ManagerDashboard',
  data() {
    return {
      manager: {
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
    document.title = 'Amethyst Hotel | Manager';
    this.fetchData();
    this.fetchImages();
  },
  methods: {
    fetchData() {
      axios
        .get('http://localhost/new-hotel-website/backend/manager.php')
        .then((response) => {
          if (response.data.error) {
            this.error = response.data.error;
          } else {
            this.manager = response.data.manager;
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

.manager-dashboard-wrapper {
  position: relative;
  max-width: 100%;
  min-height: 100vh;
  overflow-x: hidden;
  overflow-y: auto;
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

.manager-dashboard-content {
  max-width: 1200px;
  margin: 150px auto 0 auto;
  padding: 20px;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  font-family: 'Gabriela', serif;
  position: relative;
  z-index: 1;
  min-height: calc(100vh - 150px);
}

.manager-profile-section {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.manager-user-info {
  flex: 1;
}

.manager-user-info h2 {
  font-family: 'Gabriela', serif;
  margin-bottom: 10px;
  color: #6a0dad;
}

.manager-user-info p {
  font-family: 'Gabriela', serif;
  color: #333;
}

.manager-bookings-section,
.manager-wishes-section {
  margin-top: 10px;
  text-align: center;
}

.manager-bookings-section h3,
.manager-wishes-section h3 {
  font-family: 'Gabriela', serif;
  color: #6a0dad;
  margin-bottom: 10px;
  position: relative;
}

.filter-label {
  margin-right: 5px;
  font-size: 16px;
  vertical-align: middle;
  position: absolute;
  right: 30px;
}

.sort-dropdown {
  position: absolute;
  background-color: #fff;
  border: 1px solid #ccc;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  z-index: 2;
  right: 0;
}

.sort-dropdown ul {
  list-style: none;
  padding: 10px;
  margin: 0;
  text-align: right;
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
  cursor: pointer;
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
  position: absolute;
  top: -20px;
  right: 0px;
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

.manager-room-actions {
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
  .manager-dashboard-content {
    padding: 10px;
  }

  .manager-profile-section {
    flex-direction: column;
    margin-bottom: 10px;
  }

  .manager-user-info {
    text-align: center;
    margin-top: 10px;
  }

  .filter-label,
  .sort-icon-common {
    position: static;
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
  .manager-dashboard-content {
    padding: 10px;
  }

  .manager-profile-section {
    flex-direction: column;
    margin-bottom: 10px;
  }

  .manager-user-info {
    text-align: center;
    margin-top: 10px;
  }

  .filter-label,
  .sort-icon-common {
    position: static;
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

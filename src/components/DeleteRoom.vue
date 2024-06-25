<template>
    <div class="delete-room-wrapper">
      <div id="background-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
      <div class="delete-room">
        <h2>Видалити Кімнату</h2>
        <div v-if="isLoading">Завантаження...</div>
        <div v-else-if="rooms.length > 0">
          <ul>
            <li v-for="room in rooms" :key="room.id">
              <span class="room-name">{{ room.name }}</span>
              <button @click="deleteRoom(room.id)">Видалити</button>
            </li>
          </ul>
        </div>
        <p v-else>Немає кімнат для видалення.</p>
        <p v-if="error">{{ error }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'DeleteRoom',
    data() {
      return {
        rooms: [],
        error: null,
        backgroundImage: '',
        isLoading: true,
      };
    },
    mounted() {
      this.fetchRooms();
      this.fetchBackgroundImage();
      document.title = 'Amethyst Hotel | Delete Room';
    },
    methods: {
      async fetchRooms() {
        try {
          const response = await axios.get('http://localhost/new-hotel-website/backend/room.php');
          this.rooms = response.data;
          if (this.rooms.length === 0) {
            this.error = 'Немає кімнат для видалення.';
          } else {
            this.error = null;
          }
        } catch (error) {
          console.error('Error fetching rooms:', error);
          this.error = 'Помилка отримання даних про кімнати.';
        } finally {
          this.isLoading = false;
        }
      },
      async deleteRoom(id) {
        try {
          const response = await axios.post('http://localhost/new-hotel-website/backend/delete_room.php', { id });
          console.log('Server response:', response.data); // Log the server response
          if (response.data.success) {
            alert('Кімнату видалено успішно');
            this.fetchRooms();
          } else {
            alert(`Помилка видалення кімнати: ${response.data.error}`);
          }
        } catch (error) {
          console.error('Error deleting room:', error);
          alert('Помилка видалення кімнати');
        }
      },
      async fetchBackgroundImage() {
        try {
          const response = await axios.get('http://localhost/new-hotel-website/backend/get_images.php');
          const images = response.data;
          this.backgroundImage = this.findImage(images, 'login', 'login.png');
          console.log('Background image URL:', this.backgroundImage); // Debugging line
        } catch (error) {
          console.error('Error fetching images:', error);
        }
      },
      findImage(images, category, imageName) {
        const image = images.find(img => img.category === category && img.image_name === imageName);
        return image ? `http://localhost/new-hotel-website/src/assets/${image.image_name}` : '';
      }
    }
  };
  </script>
  
  <style scoped>
  .delete-room-wrapper {
    position: relative;
    min-height: 100vh;
    padding-top: 0;
    padding-bottom: 20px;
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
  
  .delete-room {
    max-width: 600px;
    margin: 0 auto;
    padding: 100px 20px 100px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  }
  
  h2 {
    font-family: 'Gabriela', serif;
    color: #6a0dad;
    text-align: center;
    margin-bottom: 20px;
  }
  
  ul {
    list-style: none;
    padding: 0;
  }
  
  li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ccc;
  }
  
  .room-name {
    font-family: 'Gabriela', serif;
  }
  
  button {
    background-color: #d9534f;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    font-family: 'Gabriela', serif;
    border-radius: 5px;
  }
  
  button:hover {
    background-color: #c9302c;
  }
  </style>
  
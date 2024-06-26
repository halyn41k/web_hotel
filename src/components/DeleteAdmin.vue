<template>
    <div class="admin-form-wrapper">
      <div id="background-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
      <div class="admin-form">
        <h2>Видалити Адміна</h2>
        <div v-if="isLoading">Завантаження...</div>
        <div v-else-if="admins.length > 0">
          <ul>
            <li v-for="admin in admins" :key="admin.id">
              <span class="admin-name">{{ admin.name }} {{ admin.surname }} ({{ admin.email }})</span>
              <button @click="deleteAdmin(admin.email)">Видалити</button>
            </li>
          </ul>
        </div>
        <p v-else>Немає адміністраторів для видалення.</p>
        <p v-if="error">{{ error }}</p>
        <p v-if="success">{{ success }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'DeleteAdmin',
    data() {
      return {
        admins: [],
        error: null,
        success: null,
        backgroundImage: '',
        isLoading: true,
      };
    },
    mounted() {
      this.fetchAdmins();
      this.fetchBackgroundImage();
      document.title = 'Amethyst Hotel | Delete Admin';
    },
    methods: {
      async fetchAdmins() {
        try {
          const response = await axios.get('http://localhost/new-hotel-website/backend/get_admins.php');
          this.admins = response.data;
          if (this.admins.length === 0) {
            this.error = 'Немає адміністраторів для видалення.';
          } else {
            this.error = null;
          }
        } catch (error) {
          console.error('Error fetching admins:', error);
          this.error = 'Помилка отримання даних про адміністраторів.';
        } finally {
          this.isLoading = false;
        }
      },
      async deleteAdmin(email) {
        try {
          const response = await axios.post('http://localhost/new-hotel-website/backend/delete_admin.php', { email });
          if (response.data.success) {
            this.success = 'Адміна видалено успішно.';
            this.fetchAdmins();
          } else {
            this.error = `Помилка видалення адміна: ${response.data.error}`;
          }
        } catch (error) {
          console.error('Error deleting admin:', error);
          this.error = 'Помилка видалення адміна';
        }
      },
      async fetchBackgroundImage() {
        try {
          const response = await axios.get('http://localhost/new-hotel-website/backend/get_images.php');
          const images = response.data;
          this.backgroundImage = this.findImage(images, 'login', 'login.png');
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
  .admin-form-wrapper {
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
  
  .admin-form {
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
  
  .admin-name {
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
  
  p {
    text-align: center;
    color: red;
  }
  </style>
  
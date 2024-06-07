<template>
    <div class="dashboard-wrapper">
      <div id="background-image"></div>
      <div class="dashboard-content">
        <div class="profile-section">
          <img :src="profileImage" alt="Profile Photo" class="profile-photo" @click="uploadImage">
          <input type="file" ref="fileInput" style="display: none;" @change="handleImageUpload">
          <div class="user-info">
            <h2>{{ name }} {{ surname }}</h2>
            <p>{{ email }}</p>
          </div>
        </div>
        <div class="bookings-section">
          <h3>Ваші бронювання:</h3>
          <ul>
            <li v-for="(booking, index) in bookings" :key="index">{{ booking }}</li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'UserDashboard',
    data() {
      return {
        name: 'Ім\'я',
        surname: 'Прізвище',
        email: 'email@example.com',
        profileImage: require('@/assets/image-removebg-preview (1аукау).png'),
        bookings: ['Бронювання 1', 'Бронювання 2', 'Бронювання 3']
      };
    },
    methods: {
      uploadImage() {
        this.$refs.fileInput.click();
      },
      handleImageUpload(event) {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.profileImage = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      }
    }
  };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap');
  
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
    font-family: 'Gabriela', sans-serif;
    position: relative;
    z-index: 1;
  }
  
  .profile-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .profile-photo {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    margin-right: 20px;
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
    font-family: 'Roboto', sans-serif;
    color: #333;
  }
  
  .bookings-section {
    margin-top: 20px;
  }
  
  .bookings-section h3 {
    font-family: 'Gabriela', serif;
    color: #6a0dad;
    margin-bottom: 10px;
  }
  
  .bookings-section ul {
    list-style-type: none;
    padding: 0;
  }
  
  .bookings-section li {
    background: #f8f8f8;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 5px;
    font-family: 'Roboto', sans-serif;
  }
  </style>
  
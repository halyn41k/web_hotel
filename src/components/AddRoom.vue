<template>
    <div class="add-room-wrapper">
      <div id="background-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
      <div class="add-room">
        <h2>Додати Кімнату</h2>
        <form @submit.prevent="addRoom" enctype="multipart/form-data">
          <div class="form-group">
            <label for="roomName">Назва кімнати:</label>
            <input type="text" v-model="roomName" required>
          </div>
          <div class="form-group">
            <label for="roomDescription">Опис Кімнати:</label>
            <textarea v-model="roomDescription" required></textarea>
          </div>
          <div class="form-group">
            <label for="roomPrice">Ціна за ніч:</label>
            <input type="number" v-model="roomPrice" required>
          </div>
          <div class="form-group">
            <label for="bedType">Тип ліжка:</label>
            <input type="text" v-model="bedType" required>
          </div>
          <div class="form-group">
            <label for="availability">Наявність:</label>
            <input type="text" v-model="availability" required>
          </div>
          <div class="form-group">
            <label for="maxGuests">Максимальна кількість гостей:</label>
            <input type="number" v-model="maxGuests" required>
          </div>
          <div class="form-group">
            <label for="additionalServices">Додаткові послуги:</label>
            <textarea v-model="additionalServices" required></textarea>
          </div>
          <div class="form-group">
            <label for="roomImage">Зображення кімнати:</label>
            <input type="file" @change="handleFileUpload">
          </div>
          <button type="submit" class="action-button">Додати кімнату</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'AddRoom',
    data() {
      return {
        roomName: '',
        roomDescription: '',
        roomPrice: '',
        bedType: '',
        availability: '',
        maxGuests: '',
        additionalServices: '',
        roomImage: null,
        backgroundImage: ''
      };
    },
    mounted() {
      this.fetchBackgroundImage();
    },
    methods: {
      handleFileUpload(event) {
        this.roomImage = event.target.files[0];
      },
      addRoom() {
        const formData = new FormData();
        formData.append('name', this.roomName);
        formData.append('description', this.roomDescription);
        formData.append('price', this.roomPrice);
        formData.append('bedType', this.bedType);
        formData.append('availability', this.availability);
        formData.append('maxGuests', this.maxGuests);
        formData.append('additionalServices', this.additionalServices);
        if (this.roomImage) {
          formData.append('image', this.roomImage);
        }
  
        axios.post('http://localhost/new-hotel-website/backend/add_room.php', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
        .then(response => {
          if (response.data.success) {
            alert('Кімнату додано успішно');
            this.$router.push('/admin');
          } else {
            alert('Помилка додавання кімнати');
          }
        })
        .catch(error => {
          console.error('Error adding room:', error);
          alert('Помилка додавання кімнати');
        });
      },
      fetchBackgroundImage() {
        axios.get('http://localhost/new-hotel-website/backend/get_images.php')
          .then(response => {
            const images = response.data;
            this.backgroundImage = this.findImage(images, 'login', 'login.png'); // Replace with your image name
          })
          .catch(error => {
            console.error('Error fetching images:', error);
          });
      },
      findImage(images, category, imageName) {
        const image = images.find(img => img.category === category && img.image_name === imageName);
        return image ? `http://localhost/new-hotel-website/src/assets/${image.image_name}` : '';
      }
    }
  };
  </script>
  
  <style scoped>
  .add-room-wrapper {
    position: relative;
    min-height: 100vh;
    padding-top: 20px;
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
  
  .add-room {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
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
  
  .form-group {
    margin-bottom: 15px;
  }
  
  label {
    display: block;
    font-family: 'Gabriela', serif;
    color: #333;
    margin-bottom: 5px;
  }
  
  input,
  textarea {
    width: calc(100% - 20px);
    padding: 10px;
    font-family: 'Gabriela', serif;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  textarea {
    resize: vertical;
    min-height: 100px;
  }
  
  .action-button {
    display: block;
    width: 100%;
    background-color: #6a0dad;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    font-family: 'Gabriela', serif;
    border-radius: 5px;
    margin-top: 10px;
  }
  
  .action-button:hover {
    background-color: #49146F;
  }
  </style>
  
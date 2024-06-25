<template>
    <div class="change-room-wrapper">
      <div id="background-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
      <div class="change-room">
        <h2>Змінити Кімнату</h2>
        <div v-if="rooms.length > 0">
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>Ім'я</th>
                  <th>Опис</th>
                  <th>Ціна</th>
                  <th>Тип ліжка</th>
                  <th>Доступність</th>
                  <th>Макс. гості</th>
                  <th>Додаткові послуги</th>
                  <th>Зображення</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="room in rooms" :key="room.id">
                  <td @dblclick="editField(room, 'name')">
                    <input v-if="editingRoom.id === room.id && editingField === 'name'" v-model="editingValue" @blur="saveField(room, 'name')">
                    <span v-else>{{ room.name }}</span>
                  </td>
                  <td @dblclick="editField(room, 'description')">
                    <textarea v-if="editingRoom.id === room.id && editingField === 'description'" v-model="editingValue" @blur="saveField(room, 'description')"></textarea>
                    <span v-else>{{ room.description }}</span>
                  </td>
                  <td @dblclick="editField(room, 'price')">
                    <input type="number" v-if="editingRoom.id === room.id && editingField === 'price'" v-model="editingValue" @blur="saveField(room, 'price')">
                    <span v-else>{{ room.price }}</span>
                  </td>
                  <td @dblclick="editField(room, 'bedType')">
                    <input v-if="editingRoom.id === room.id && editingField === 'bedType'" v-model="editingValue" @blur="saveField(room, 'bedType')">
                    <span v-else>{{ room.bedType }}</span>
                  </td>
                  <td @dblclick="editField(room, 'availability')">
                    <input v-if="editingRoom.id === room.id && editingField === 'availability'" v-model="editingValue" @blur="saveField(room, 'availability')">
                    <span v-else>{{ room.availability }}</span>
                  </td>
                  <td @dblclick="editField(room, 'maxGuests')">
                    <input type="number" v-if="editingRoom.id === room.id && editingField === 'maxGuests'" v-model="editingValue" @blur="saveField(room, 'maxGuests')">
                    <span v-else>{{ room.maxGuests }}</span>
                  </td>
                  <td @dblclick="editField(room, 'additionalServices')">
                    <textarea v-if="editingRoom.id === room.id && editingField === 'additionalServices'" v-model="editingValue" @blur="saveField(room, 'additionalServices')"></textarea>
                    <span v-else>{{ room.additionalServices }}</span>
                  </td>
                  <td>
                    <input type="file" @change="handleFileUpload($event, room)" />
                    <img :src="room.imageUrl" alt="Room Image" v-if="room.imageUrl" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <p v-else>Немає кімнат для зміни.</p>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'ChangeRoom',
    data() {
      return {
        rooms: [],
        editingRoom: {},
        editingField: '',
        editingValue: '',
        backgroundImage: ''
      };
    },
    created() {
      this.fetchRooms();
      this.fetchBackgroundImage();
    },
    methods: {
      fetchRooms() {
        axios.get('http://localhost/new-hotel-website/backend/get_rooms.php')
          .then(response => {
            if (response.data && response.data.rooms) {
              this.rooms = response.data.rooms;
            } else {
              console.error('Unexpected response format:', response);
            }
          })
          .catch(error => {
            console.error('Error fetching rooms:', error);
          });
      },
      editField(room, field) {
        this.editingRoom = room;
        this.editingField = field;
        this.editingValue = room[field];
      },
      saveField(room, field) {
        const updatedData = { id: room.id, field: field, value: this.editingValue };
  
        axios.post('http://localhost/new-hotel-website/backend/update_room.php', updatedData)
          .then(response => {
            if (response.data.success) {
              room[field] = this.editingValue;
              this.editingRoom = {};
              this.editingField = '';
              alert('Кімнату оновлено успішно');
            } else {
              alert('Помилка оновлення кімнати');
            }
          })
          .catch(error => {
            console.error('Error updating room:', error);
            alert('Помилка оновлення кімнати');
          });
      },
      handleFileUpload(event, room) {
        const file = event.target.files[0];
        const formData = new FormData();
        formData.append('id', room.id);
        formData.append('image', file);
  
        axios.post('http://localhost/new-hotel-website/backend/update_room_image.php', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
        .then(response => {
          if (response.data.success) {
            alert('Зображення кімнати оновлено успішно');
            this.fetchRooms();
          } else {
            alert('Помилка оновлення зображення кімнати');
          }
        })
        .catch(error => {
          console.error('Error updating room image:', error);
          alert('Помилка оновлення зображення кімнати');
        });
      },
      fetchBackgroundImage() {
        axios.get('http://localhost/new-hotel-website/backend/get_images.php')
          .then(response => {
            const images = response.data;
            this.backgroundImage = this.findImage(images, 'login', 'login.png');
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
  @import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');
  
  * {
    font-family: 'Gabriela', serif;
  }
  
  .change-room-wrapper {
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
  
  .change-room {
    max-width: 1200px;
    margin: 0 auto;
    padding: 100px 20px 100px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  }
  
  .table-container {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
    overflow-x: auto;
  }
  
  h2 {
    font-family: 'Gabriela', serif;
    color: #6a0dad;
    text-align: center;
    margin-bottom: 20px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th {
    background-color: #49146F;
    color: white;
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
  }
  
  td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
  }
  
  input, textarea {
    width: 100%;
    padding: 5px;
    margin-bottom: 10px;
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
  
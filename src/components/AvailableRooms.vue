<template>
    <div class="room-list">
      <div v-for="(room, index) in rooms" :key="index" class="room-card">
        <img :src="room.photo" class="room-photo" :alt="room.name" @click="toggleZoom(index)"
          :class="{ 'zoomed': isZoomed[index] }" />
        <div class="room-details">
          <h2 class="room-name">{{ room.name }}</h2>
          <p class="room-description">{{ room.description }}</p>
          <p class="room-price">Ціна за ніч: {{ room.price }} грн</p>
          <p class="room-bed-type">Тип ліжка: {{ room.bedType }}</p>
          <p class="room-availability">Доступність: {{ room.availability }}</p>
          <p class="room-max-guests">Максимальна кількість гостей: {{ room.maxGuests }}</p>
          <p class="room-additional-services">Додаткові послуги: {{ room.additionalServices }}</p>
          <div class="button-container">
            <button class="details-button" @click="selectRoom(room)">Забронювати</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AvailableRooms',
    props: {
      rooms: Array,
    },
    data() {
      return {
        isZoomed: new Array(this.rooms.length).fill(false), 
      };
    },
    methods: {
      toggleZoom(index) {
        this.isZoomed[index] = !this.isZoomed[index];
      },
      selectRoom(room) {
        this.$emit('room-selected', room);
      },
    },
  };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap');
  
  .room-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 90px 10px;
    background-color: #f0f0f0; 
  }
  
  .room-card {
    margin: 20px;
    padding: 20px;
    border-radius: 10px;
    width: calc(25% - 40px);
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background-color: #fff;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease, transform 0.3s ease;
  }
  
  .room-card:hover {
    box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.2);
    transform: translateY(-10px);
  }
  
  .room-photo {
    width: 100%;
    border-radius: 10px;
  }
  
  .room-details {
    margin-top: 10px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  
  .room-name,
  .room-description,
  .room-price,
  .room-bed-type,
  .room-availability,
  .room-max-guests,
  .room-additional-services {
    font-family: 'Lora', serif;
    margin: 4px 0;
  }
  
  .room-name {
    font-size: 1.5em;
  }
  
  .room-description {
    margin-top: 6px;
  }
  
  .room-price {
    font-size: 18px;
    font-weight: bold;
    margin-top: 6px;
  }
  
  .button-container {
    display: flex;
    justify-content: center;
    margin-top: 10px;
  }
  
  .details-button {
    background-color: #e67e22;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    font-family: 'Gabriela', serif;
  }
  
  .details-button:hover {
    background-color: #6a5acd;
  }
  
  .room-photo.zoomed {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    max-width: 50%;
    max-height: 70%;
    cursor: zoom-out;
  }
  
  .room-photo.zoomed:hover {
    cursor: zoom-out;
  }
  </style>
  
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
          <button class="details-button" @click="openBookingForm">Забронювати</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'RoomList',
  data() {
    return {
      rooms: [
        {
          name: 'Люкс "Сонячний"',
          photo: require('@/assets/729a4fa082a5edc17eab279dd41c87f7.jpg'),
          description: "Просторий люкс з чудовим видом на сонце.",
          price: 2500,
          bedType: 'Двомісний',
          availability: 'Вільно',
          maxGuests: 2,
          additionalServices: 'SPA, велика ванна'
        },
        {
          name: 'Номер "Мегаполіс Мрій"',
          photo: require('@/assets/102816Yotel03.webp'),
          description: "Затишний номер з видом на хмарочоси.",
          price: 3500,
          bedType: 'Двомісний',
          availability: 'Заброньовано',
          maxGuests: 2,
          additionalServices: 'Сніданок'
        },
        {
          name: 'Люкс "Оаза Затишку"',
          photo: require('@/assets/9954929b8c5f8e5fef03f9acb136498e.jpg'),
          description: "Елегантний люкс з власним душем та телевізором.",
          price: 3000,
          bedType: 'Двомісний',
          availability: 'Вільно',
          maxGuests: 2,
          additionalServices: 'Телевізор, душ'
        },
        {
          name: 'Номер "Романтичний вечір"',
          photo: require('@/assets/399085717.jpg'),
          description: "Атмосферний номер для романтичного вечора.",
          price: 1800,
          bedType: 'Двомісний',
          availability: 'Вільно',
          maxGuests: 2,
          additionalServices: 'Телевізор, шампанське'
        },
        {
          name: 'Люкс "Роскішна діяльність"',
          photo: require('@/assets/cl_xs1.jpg'),
          description: "Сучасний інтер'єр у поєднанні з вишуканим комфортом.",
          price: 4000,
          bedType: 'Двомісний',
          availability: 'Вільно',
          maxGuests: 2,
          additionalServices: 'Бар, тренажерний зал'
        },
        {
          name: 'Номер "Комфортне життя"',
          photo: require('@/assets/desc_0SUAupCbJIoVoq.jpg'),
          description: "Номер для сім'ї для комфортного проживання.",
          price: 5000,
          bedType: 'Чотирьохмісний',
          availability: 'Вільно',
          maxGuests: 2,
          additionalServices: 'Телевізор'
        },
        {
          name: 'Люкс "Фіолетова абстракція"',
          photo: require('@/assets/hotel-purple (1).jpg'),
          description: "Ексклюзивний номер з вражаючим інтер’єром у стилі абстракції.",
          price: 1000,
          bedType: 'Двомісний',
          availability: 'Вільно',
          maxGuests: 2,
          additionalServices: 'Сауна, джакузі'
        },
      ],
      isZoomed: new Array(7).fill(false) // Початково всі фотографії не збільшені
    };
  },
  methods: {
    toggleZoom(index) {
      this.isZoomed[index] = !this.isZoomed[index];
    },
    openBookingForm() {
      this.$router.push('/booking');
    }
  },
  mounted() {
    this.fetchRooms();
  }
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

/* Media Queries for Responsive Design */

@media (max-width: 1200px) {
  .room-card {
    width: calc(33.33% - 40px); /* Adjusted width for tablets and small desktops */
  }
}

@media (max-width: 768px) {
  .room-list {
    padding: 20px;
  }
  .room-card {
    width: calc(50% - 40px); /* Adjusted width for small tablets */
  }
}

@media (max-width: 480px) {
  .room-list {
    padding: 10px;
  }
  .room-card {
    width: calc(100% - 40px); /* Adjusted width for mobile devices */
  }
  .room-name {
    font-size: 1.2em; /* Smaller font size for mobile */
  }
  .room-description,
  .room-price,
  .room-bed-type,
  .room-availability,
  .room-max-guests,
  .room-additional-services {
    font-size: 0.9em; /* Smaller font size for mobile */
  }
  .details-button {
    font-size: 16px; /* Smaller button font size for mobile */
  }
}
</style>

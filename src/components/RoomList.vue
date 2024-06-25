<<<<<<< Updated upstream
<template>
  <div class="room-list">
    <h3 class="sort-header">
      <span class="filter-label">Фільтр</span>
      <img :src="filterIcon" @click="toggleSortDropdown('rooms')" alt="Sort" class="sort-icon">
    </h3>
    <div v-if="showSortRoomsDropdown" class="sort-dropdown">
      <ul>
        <li @click="sortRooms('price', 'asc')">Ціна за зростанням</li>
        <li @click="sortRooms('price', 'desc')">Ціна за спаданням</li>
        <li @click="sortRooms('bedType', 'asc')">Тип ліжка за зростанням</li>
        <li @click="sortRooms('bedType', 'desc')">Тип ліжка за спаданням</li>
      </ul>
    </div>
    <div class="rooms-container">
      <div v-for="(room, index) in rooms" :key="index" class="room-card">
        <img :src="room.photo" class="room-photo" :alt="room.name" @click="toggleZoom(index)" />
        <div class="room-details">
          <h2 class="room-name">{{ room.name }}</h2>
          <p class="room-description">{{ room.description }}</p>
          <p class="room-price">Ціна за ніч: {{ room.price }} грн</p>
          <p class="room-bed-type">Тип ліжка: {{ room.bedType }}</p>
          <p class="room-availability">Доступність: {{ room.availability }}</p>
          <p class="room-max-guests">Максимальна кількість гостей: {{ room.maxGuests }}</p>
          <p class="room-additional-services">Додаткові послуги: {{ room.additionalServices }}</p>
          <div class="button-container">
            <button class="details-button" @click="openBookingForm(room)">Забронювати</button>
          </div>
    <h3 class="sort-header">
      <span class="filter-label">Фільтр</span>
      <img :src="filterIcon" @click="toggleSortDropdown('rooms')" alt="Sort" class="sort-icon">
    </h3>
    <div v-if="showSortRoomsDropdown" class="sort-dropdown">
      <ul>
        <li @click="sortRooms('price', 'asc')">Ціна за зростанням</li>
        <li @click="sortRooms('price', 'desc')">Ціна за спаданням</li>
        <li @click="sortRooms('bedType', 'asc')">Тип ліжка за зростанням</li>
        <li @click="sortRooms('bedType', 'desc')">Тип ліжка за спаданням</li>
      </ul>
    </div>
    <div class="rooms-container">
      <div v-for="(room, index) in rooms" :key="index" class="room-card">
        <img :src="room.photo" class="room-photo" :alt="room.name" @click="toggleZoom(index)" />
        <div class="room-details">
          <h2 class="room-name">{{ room.name }}</h2>
          <p class="room-description">{{ room.description }}</p>
          <p class="room-price">Ціна за ніч: {{ room.price }} грн</p>
          <p class="room-bed-type">Тип ліжка: {{ room.bedType }}</p>
          <p class="room-availability">Доступність: {{ room.availability }}</p>
          <p class="room-max-guests">Максимальна кількість гостей: {{ room.maxGuests }}</p>
          <p class="room-additional-services">Додаткові послуги: {{ room.additionalServices }}</p>
          <div class="button-container">
            <button class="details-button" @click="openBookingForm(room)">Забронювати</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RoomList',
  data() {
    return {
      rooms: [],
      isZoomed: [],
      showSortRoomsDropdown: false,
      sortField: '',
      sortOrder: '',
      filterIcon: '',
      isZoomed: [],
      showSortRoomsDropdown: false,
      sortField: '',
      sortOrder: '',
      filterIcon: '',
    };
  },
  mounted() {
    console.log("Component mounted");
    console.log("Component mounted");
    this.fetchRooms();
    document.title = 'Amethyst Hotel | Room List';
  },
  methods: {
    fetchRooms() {
      console.log("Fetching rooms");
      fetch('http://localhost/new-hotel-website/backend/room.php')
        .then(response => {
          console.log("Room fetch response received");
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
          }
          return response.json();
        })
      console.log("Fetching rooms");
      fetch('http://localhost/new-hotel-website/backend/room.php')
        .then(response => {
          console.log("Room fetch response received");
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
          }
          return response.json();
        })
        .then(data => {
          console.log("Room data:", data);
          console.log("Room data:", data);
          this.rooms = data;
          this.isZoomed = new Array(data.length).fill(false);

          return fetch('http://localhost/new-hotel-website/backend/get_images.php');
        })
        .then(response => {
          console.log("Images fetch response received");
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
          }
          return response.json();
        })
        .then(images => {
          console.log("Images data:", images);
          this.rooms.forEach(room => {
            const image = images.find(img => parseInt(img.room_id) === room.id);
            if (image) {
              room.photo = this.getImageUrl(image.image_name);
              console.log(`Assigned image ${image.image_name} to room ${room.name}`);
            } else {
              console.log(`No image found for room ${room.name}`);
            }
          });

          const filterIconImage = images.find(img => img.category === 'admin' && img.image_name === 'image-removebg-preview 213.png');
          if (filterIconImage) {
            this.filterIcon = this.getImageUrl(filterIconImage.image_name);
            console.log("Filter icon image assigned");
          } else {
            console.log("Filter icon image not found");
          }
        })
        .catch(error => console.error('Error fetching data:', error));
    },
    getImageUrl(imageName) {
      const imageUrl = `http://localhost/new-hotel-website/src/assets/${imageName}`;
      console.log("Image URL:", imageUrl);
      return imageUrl;
    },
    toggleZoom(index) {
      this.isZoomed[index] = !this.isZoomed[index];
    },
    openBookingForm(room) {
      this.$router.push({ path: '/booking', query: { room: JSON.stringify(room) } });
    },
    toggleSortDropdown(section) {
      if (section === 'rooms') {
        this.showSortRoomsDropdown = !this.showSortRoomsDropdown;
      }
    },
    sortRooms(field, order) {
      this.sortField = field;
      this.sortOrder = order;
      this.rooms.sort((a, b) => {
        let comparison = 0;
        if (a[field] > b[field]) {
          comparison = 1;
        } else if (a[field] < b[field]) {
          comparison = -1;
        }
        return order === 'asc' ? comparison : -comparison;
      });
      this.showSortRoomsDropdown = false;
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');

.room-list {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 90px 10px;
  background-color: #f0f0f0;
}

.sort-header {
  display: flex;
  align-items: center;
}

.filter-label {
  font-family: 'Gabriela', serif;
  font-size: 1.5em;
  margin-right: 10px;
}

.sort-icon {
  cursor: pointer;
  width: 30px;
  height: 30px;
}

.sort-dropdown {
  position: absolute;
  background-color: #fff;
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
  margin-top: 10px;
}

.sort-dropdown ul {
  list-style-type: none;
  padding: 10px;
}

.sort-dropdown li {
  font-family: 'Gabriela', serif;
  padding: 10px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.sort-dropdown li:hover {
  background-color: #f0f0f0;
}

.rooms-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  width: 100%;
}

.room-card {
  display: flex;
  flex-direction: row;
  margin: 20px;
  padding: 20px;
  border-radius: 10px;
  width: calc(50% - 50px);
  box-sizing: border-box;
  background-color: #fff;
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.room-card:hover {
  box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.2);
  transform: translateY(-10px);
}

.room-photo {
  width: 50%;
  height: auto;
  width: 50%;
  height: auto;
  border-radius: 10px;
  margin-right: 20px;
}

.room-details {
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
  font-family: 'Gabriela', serif;
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
  background-color: #d35400;
}

/* Responsive styles */
@media (max-width: 768px) {
  .room-card {
    flex-direction: column;
    width: 100%;
  }

  .room-photo {
    width: 100%;
    height: auto;
    margin: 0 0 20px 0;
  }

  .room-details {
    align-items: center;
    text-align: center;
  }
}

@media (max-width: 480px) {
  .filter-label {
    font-size: 1.2em;
  }

  .sort-dropdown li {
    font-size: 0.9em;
  }

  .room-name {
    font-size: 1.2em;
  }

  .room-price {
    font-size: 16px;
  }

  .details-button {
    font-size: 16px;
  }
}
</style>


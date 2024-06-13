<template>
  <div class="dashboard-wrapper">
    <div id="background-image"></div>
    <div class="dashboard-content">
      <div class="profile-section">
        <div class="profile-photo-wrapper">
          <img :src="profileImage" alt="Profile Photo" class="profile-photo" :class="{ 'uploading': uploadingImage }">
          <button class="upload-button" @click="uploadImage" :disabled="uploadingImage">
            {{ uploadingImage ? 'Завантаження...' : 'Завантажити фото' }}
          </button>
          <input type="file" ref="fileInput" style="display: none;" @change="handleImageUpload">
        </div>
        <div class="user-info">
          <h2>{{ name }} {{ surname }}</h2>
          <p>Email: {{ email }}</p>
          <p>Номер телефону: {{ phone }}</p>
          <p>Спосіб оплати: {{ paymentMethod }}</p>
          <button class="change-payment-button" @click="togglePaymentMethod">Змінити спосіб оплати</button>
        </div>
      </div>
      <div class="bookings-section">
        <h3>Ваші бронювання:</h3>
        <ul v-if="bookings.length > 0">
          <li v-for="(booking, index) in bookings" :key="index">
            <p><strong>Дата заїзду:</strong> {{ booking.checkin }}</p>
            <p><strong>Дата виїзду:</strong> {{ booking.checkout }}</p>
            <p><strong>Номер кімнати:</strong> {{ booking.room }}</p>
            <p><strong>Ціна:</strong> {{ booking.price }} грн/ніч</p>
            <button class="delete-booking-button" @click="deleteBooking(index)">Видалити бронювання</button>
          </li>
        </ul>
        <p v-else>У вас немає бронювань.</p>
        <button v-if="bookings.length === 0" class="book-room-button" @click="bookRoom">Забронювати кімнату</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserDashboard',
  data() {
    return {
      name: '',
      surname: '',
      email: '',
      phone: '',
      paymentMethod: '',
      profileImage: '',
      defaultProfileImage: require('@/assets/2431414134.png'),
      bookings: [],
      uploadingImage: false
    };
  },
  methods: {
    uploadImage() {
      this.$refs.fileInput.click();
    },
    async handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.uploadingImage = true;
        const formData = new FormData();
        formData.append('profile_photo', file);

        try {
          const response = await fetch('http://localhost/new-hotel-website/user.php?action=uploadProfilePhoto', {
            method: 'POST',
            body: formData
          });
          const data = await response.json();
          if (data.profile_photo) {
            this.profileImage = data.profile_photo;
          } else {
            console.error(data.error);
          }
        } catch (error) {
          console.error('Помилка завантаження фото:', error);
        } finally {
          this.uploadingImage = false;
        }
      }
    },
    bookRoom() {
      this.$router.push('/booking');
    },
    togglePaymentMethod() {
      const availablePaymentMethods = ['Оплата в готелі', 'Картка'];
      const currentIndex = availablePaymentMethods.indexOf(this.paymentMethod);
      const nextIndex = (currentIndex + 1) % availablePaymentMethods.length;
      this.paymentMethod = availablePaymentMethods[nextIndex];
      this.updatePaymentMethod();
    },
    async updatePaymentMethod() {
      try {
        const response = await fetch('http://localhost/new-hotel-website/user.php?action=updatePaymentMethod', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ paymentMethod: this.paymentMethod })
        });
        const data = await response.json();
        if (data.success) {
          console.log('Спосіб оплати оновлено');
        } else {
          console.error(data.error);
        }
      } catch (error) {
        console.error('Помилка оновлення способу оплати:', error);
      }
    },
    deleteBooking(index) {
      this.bookings.splice(index, 1);
      localStorage.setItem('userData', JSON.stringify({
        name: this.name,
        surname: this.surname,
        email: this.email,
        phone: this.phone,
        paymentMethod: this.paymentMethod,
        profileImage: this.profileImage,
        bookings: this.bookings
      }));
    }
  },
  created() {
    const userData = localStorage.getItem('userData');
    if (userData) {
      const { name, surname, email, phone, paymentMethod, profileImage, bookings } = JSON.parse(userData);
      this.name = name;
      this.surname = surname;
      this.email = email;
      this.phone = phone;
      this.paymentMethod = paymentMethod;
      this.profileImage = profileImage || this.defaultProfileImage;
      this.bookings = bookings || [];
    } else {
      this.$router.push('/registration');
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');

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
  font-family: 'Gabriela', serif;
  position: relative;
  z-index: 1;
}

.profile-section {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.profile-photo-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-right: 20px;
  margin-left: 50px;
}

.profile-photo {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  cursor: pointer;
  margin-bottom: 10px;
  transition: opacity 0.3s ease-in-out;
}

.profile-photo.uploading {
  opacity: 0.5;
}

.upload-button {
  padding: 10px 20px;
  background-color: #6a0dad;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
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
  font-family: 'Gabriela', serif; /* Change this to your desired classic font */
  color: #333;
}

.user-info p:first-child {
  font-family: 'Gabriela', serif; /* Apply Gabriela font to the first paragraph */
}

.change-payment-button {
  padding: 10px 20px;
  background-color: #6a0dad;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
  margin-top: 10px;
}

.bookings-section {
  margin-top: 10px;
  text-align: center;
}

.bookings-section h3 {
  font-family: 'Gabriela', serif;
  color: #6a0dad;
  margin-bottom: 1px;
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
  font-family: 'Gabriela', serif; /* Change this to your desired classic font */
  position: relative;
}

.delete-booking-button {
  position: absolute;
  top: 175px; /* Adjusted to be 10px from the bottom */
  left: 50%;
  transform: translateX(-50%);
  padding: 5px 10px;
  background-color: #ff6347;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
}

.book-room-button {
  position: absolute;
  bottom: 15px; /* Adjusted to be 15px from the bottom */
  left: 50%;
  transform: translateX(-50%);
  padding: 15px 30px;
  background-color: #6a0dad;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Gabriela', serif;
  font-size: 18px;
  transition: background-color 0.3s;
}

.book-room-button:hover {
  background-color: #6a0dad;
}

@media screen and (max-width: 768px) {
  .dashboard-content {
    padding: 10px;
  }
  
  .profile-section {
    flex-direction: column;
    margin-bottom: 10px;
  }
  
  .profile-photo-wrapper {
    margin: 0;
    margin-bottom: 10px;
  }
  
  .user-info {
    text-align: center;
    margin-top: 10px;
  }
  
  .book-room-button,
  .delete-booking-button {
    position: static;
    margin-top: 20px;
    transform: none;
    width: 100%;
  }
}
</style>


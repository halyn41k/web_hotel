<template>
  <div class="admin-form-wrapper">
    <div id="background-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
    <div class="admin-form">
      <h2>Додати Адміна</h2>
      <form @submit.prevent="validateForm">
        <div class="form-group">
          <label for="name">Ім'я</label>
          <input type="text" id="name" v-model="name" @blur="validateName" required>
          <p v-if="nameError">{{ nameError }}</p>
        </div>
        <div class="form-group">
          <label for="surname">Прізвище</label>
          <input type="text" id="surname" v-model="surname" @blur="validateSurname" required>
          <p v-if="surnameError">{{ surnameError }}</p>
        </div>
        <div class="form-group">
          <label for="email">Електронна пошта</label>
          <input type="email" id="email" v-model="email" @blur="validateEmail" required>
          <p v-if="emailError">{{ emailError }}</p>
        </div>
        <div class="form-group">
          <label for="phone">Номер телефону</label>
          <input type="tel" id="phone" v-model="phone" @blur="validatePhone" required>
          <p v-if="phoneError">{{ phoneError }}</p>
        </div>
        <div class="form-group">
          <label for="password">Пароль</label>
          <div class="password-wrapper">
            <input :type="passwordFieldType" id="password" v-model="password" @blur="validatePassword" required>
            <img :src="passwordToggleIcon" @click="togglePasswordVisibility" class="password-toggle-icon" />
          </div>
          <p v-if="passwordError">{{ passwordError }}</p>
        </div>
        <button type="submit" class="action-button">Додати Адміна</button>
      </form>
      <p v-if="error">{{ error }}</p>
      <p v-if="success">{{ success }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AddAdmin',
  data() {
    return {
      name: '',
      surname: '',
      email: '',
      phone: '',
      password: '',
      error: null,
      success: null,
      backgroundImage: '',
      nameError: '',
      surnameError: '',
      emailError: '',
      phoneError: '',
      passwordError: '',
      passwordVisible: false,
      eyeIcon: '',
      hideIcon: ''
    };
  },
  mounted() {
    this.fetchBackgroundImage();
    this.fetchIcons();
  },
  computed: {
    passwordFieldType() {
      return this.passwordVisible ? 'text' : 'password';
    },
    passwordToggleIcon() {
      return this.passwordVisible ? this.hideIcon : this.eyeIcon;
    }
  },
  methods: {
    validateName() {
      if (this.name.length < 2) {
        this.nameError = "Ім'я повинно бути довжиною не менше 2 символів.";
      } else {
        this.nameError = '';
      }
    },
    validateSurname() {
      if (this.surname.length < 2) {
        this.surnameError = "Прізвище повинно бути довжиною не менше 2 символів.";
      } else {
        this.surnameError = '';
      }
    },
    validateEmail() {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(this.email)) {
        this.emailError = "Введіть валідну електронну адресу.";
      } else {
        this.emailError = '';
      }
    },
    validatePhone() {
      const phonePattern = /^\+?\d{10,14}$/;
      if (!phonePattern.test(this.phone)) {
        this.phoneError = "Введіть валідний номер телефону.";
      } else {
        this.phoneError = '';
      }
    },
    validatePassword() {
      if (this.password.length < 8) {
        this.passwordError = "Пароль повинен бути довжиною не менше 8 символів.";
      } else {
        this.passwordError = '';
      }
    },
    validateForm() {
      this.validateName();
      this.validateSurname();
      this.validateEmail();
      this.validatePhone();
      this.validatePassword();
      
      if (!this.nameError && !this.surnameError && !this.emailError && !this.phoneError && !this.passwordError) {
        this.addAdmin();
      }
    },
    addAdmin() {
      axios.post('http://localhost/new-hotel-website/backend/add_admin.php', {
        name: this.name,
        surname: this.surname,
        email: this.email,
        phone: this.phone,
        password: this.password
      })
      .then(response => {
        if (response.data.error) {
          this.error = response.data.error;
        } else {
          this.success = 'Адміна додано успішно.';
          this.name = '';
          this.surname = '';
          this.email = '';
          this.phone = '';
          this.password = '';
        }
      })
      .catch(error => {
        this.error = 'Помилка додавання адміна.';
        console.error('Error adding admin:', error);
      });
    },
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
    async fetchBackgroundImage() {
      try {
        const response = await axios.get('http://localhost/new-hotel-website/backend/get_images.php');
        const images = response.data;
        this.backgroundImage = this.findImage(images, 'login', 'login.png'); // Replace with your image name
      } catch (error) {
        console.error('Error fetching images:', error);
      }
    },
    async fetchIcons() {
      try {
        const response = await axios.get('http://localhost/new-hotel-website/backend/get_images.php');
        const images = response.data;
        const eyeIconImage = images.find(img => img.category === 'login' && img.image_name === 'eye.png');
        const hideIconImage = images.find(img => img.category === 'login' && img.image_name === 'hide.png');

        if (eyeIconImage) {
          this.eyeIcon = this.getImageUrl(eyeIconImage.image_name);
        } else {
          console.error('Eye icon image not found.');
        }

        if (hideIconImage) {
          this.hideIcon = this.getImageUrl(hideIconImage.image_name);
        } else {
          console.error('Hide icon image not found.');
        }
      } catch (error) {
        console.error('Error fetching icons:', error);
      }
    },
    findImage(images, category, imageName) {
      const image = images.find(img => img.category === category && img.image_name === imageName);
      return image ? `http://localhost/new-hotel-website/src/assets/${image.image_name}` : '';
    },
    getImageUrl(imageName) {
      return `http://localhost/new-hotel-website/src/assets/${imageName}`;
    }
  }
};
</script>

<style scoped>
.admin-form-wrapper {
  position: relative;
  min-height: 100vh;
  padding-top: 100px;
  padding-bottom: 20px;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding-top: 100px; /* Adjust this value to add more or less space at the top */
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
  width: 100%;
  margin: 0 20px;
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

.password-wrapper {
  display: flex;
  align-items: center;
}

.password-wrapper input {
  flex: 1;
}

.password-toggle-icon {
  width: 24px;
  height: 24px;
  margin-left: 8px;
  cursor: pointer;
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

p {
  text-align: center;
  color: red;
}

.error-message {
  color: red;
}
</style>

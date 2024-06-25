<template>
  <footer class="footer">
    <div class="brand">
      <span class="hotel-name">AMETHYST</span>
    </div>
    <div class="contact-info">
      <div class="contact-item" v-if="footerImages.phoneIcon1">
        <img :src="footerImages.phoneIcon1" alt="Phone Icon" class="icon">
        <p class="phone">+380900000000</p>
      </div>
      <div class="contact-item" v-if="footerImages.phoneIcon2">
        <img :src="footerImages.phoneIcon2" alt="Phone Icon" class="icon">
        <p class="phone">+380600600600</p>
      </div>
      <div class="contact-item" v-if="footerImages.addressIcon">
        <img :src="footerImages.addressIcon" alt="Address Icon" class="icon">
        <p class="phone">Коломия, вул. Бульвар Лесі Українки, 7</p>
      </div>
      <div class="contact-item" v-if="footerImages.emailIcon">
        <img :src="footerImages.emailIcon" alt="Email Icon" class="icon">
        <p class="phone">Email: amethysthotel@email.com</p>
      </div>
    </div>
    <div class="copyright">
      <p class="copyright-text">© AMETHYST HOTEL 2024</p>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter',
  data() {
    return {
      footerImages: {
        phoneIcon1: '',
        phoneIcon2: '',
        addressIcon: '',
        emailIcon: ''
      }
    };
  },
  methods: {
    async fetchFooterImages() {
      try {
        const response = await fetch('http://localhost/new-hotel-website/backend/get_images.php');
        const images = await response.json();

        // Assign images based on their names
        this.footerImages.phoneIcon1 = this.getImageUrl(images.find(img => img.category === 'footer' && img.image_name === 'image-removebg-preview.png').image_name);
        this.footerImages.phoneIcon2 = this.getImageUrl(images.find(img => img.category === 'footer' && img.image_name === 'image-removebg-preview.png').image_name);
        this.footerImages.addressIcon = this.getImageUrl(images.find(img => img.category === 'footer' && img.image_name === 'Gue1ChAowFiEPm89QHEDF-transformed.png').image_name);
        this.footerImages.emailIcon = this.getImageUrl(images.find(img => img.category === 'footer' && img.image_name === 'image-removebg-preview (1).png').image_name);
      } catch (error) {
        console.error('Error fetching footer images:', error);
      }
    },
    getImageUrl(imageName) {
      return `http://localhost/new-hotel-website/src/assets/${imageName}`;
    }
  },
  mounted() {
    this.fetchFooterImages();
  }
};
</script>

<style scoped>
html, body {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  width: 100%;
}

*,
*::before,
*::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html, body {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  overflow-x: hidden;
}

body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.footer {
  background-color: #6a0dad;
  padding: 20px;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  width: 100vw;
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;
  margin-top: auto; /* Ensures footer stays at the bottom */
  overflow: hidden; /* Ensure footer does not overflow */
}

.brand {
  font-family: 'Gabriela', bold;
}

.hotel-name {
  color: #fff;
  font-family: 'Voltaire', bold;
  font-size: 36px;
}

.contact-info {
  display: flex;
  flex-direction: column;
  text-align: right;
  margin-left: auto;
}

.contact-item {
  display: flex;
  align-items: center;
  margin-bottom: 5px;
}

.icon {
  width: 20px;
  height: 20px;
  margin-right: 10px;
}

.phone {
  margin: 0;
  color: #fff;
  font-family: 'Gabriela', serif;
}

.copyright {
  width: 100%;
  text-align: center;
  margin-top: 10px;
}

.copyright-text {
  color: #d8b2f5;
  font-family: 'Gabriela', serif;
}

@media (max-width: 768px) {
  .footer {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .contact-info {
    align-items: center;
    margin-left: 0;
  }

  .contact-item {
    justify-content: center;
    text-align: center;
  }

  .brand, .contact-info {
    width: 100%;
  }

  .hotel-name {
    font-size: 28px;
  }

  .phone {
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .footer {
    padding: 10px;
  }

  .hotel-name {
    font-size: 24px;
  }

  .icon {
    width: 16px;
    height: 16px;
  }

  .phone {
    font-size: 12px;
  }
}

</style>

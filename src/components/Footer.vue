<template>
  <footer class="footer">
    <div class="brand">
      <span class="hotel-name">AMETHYST</span>
    </div>
    <div class="contact-info">
      <div class="contact-item">
        <img src="@/assets/image-removebg-preview.png" alt="Phone Icon" class="icon">
        <p class="phone">+380900000000</p>
      </div>
      <div class="contact-item">
        <img src="@/assets/image-removebg-preview.png" alt="Phone Icon" class="icon">
        <p class="phone">+380600600600</p>
      </div>
      <div class="contact-item">
        <img src="@/assets/Gue1ChAowFiEPm89QHEDF-transformed.png" alt="Address Icon" class="icon">
        <p class="phone">Коломия, вул. Бульвар Лесі Українки, 7</p>
      </div>
      <div class="contact-item">
        <img src="@/assets/image-removebg-preview (1).png" alt="Email Icon" class="icon">
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
  name: 'Footer'
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

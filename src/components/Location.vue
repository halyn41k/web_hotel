<template>
  <div class="location">
    <div class="text">
      <h2>Запрошуємо Вас до Івано-Франківської області, міста Коломия</h2>
      <h2>Адреса: вул. Бульвар Лесі Українки, 7</h2>
      <img src="@/assets/21314.png" alt="Коломия" class="location-image">
    </div>
    <div class="google-map">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2642.1865588704154!2d25.03926067632093!3d48.529656371289455!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4736d28545ff23d3%3A0xdea24e7a9f84be24!2z0LHRg9C70YzQstCw0YAg0JvQtdGB0ZYg0KPQutGA0LDRl9C90LrQuCwgNywg0JrQvtC70L7QvNC40Y8sINCG0LLQsNC90L4t0KTRgNCw0L3QutGW0LLRgdGM0LrQsCDQvtCx0LvQsNGB0YLRjCwgNzgyMDA!5e0!3m2!1suk!2sua!4v1717148194051!5m2!1suk!2sua" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppLocation',
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');

html, body {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
}

.location {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 50px;
  padding: 20px;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  border-radius: 10px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.text {
  width: 100%;
  font-family: 'Gabriela', serif;
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 20px;
  background-color: rgba(255, 255, 255, 0.8);
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.text h2 {
  color: #333;
  margin-bottom: 15px;
  font-size: 1.2em;
}

.location-image {
  margin-top: 10px;
  max-width: 100%;
}

.google-map {
  width: 100%;
  height: 400px;
  margin-top: 20px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.google-map iframe {
  width: 100%;
  height: 100%;
  border: none;
}

@media (min-width: 768px) {
  .location {
    flex-direction: row;
    justify-content: space-between;
    padding: 50px 100px;
  }

  .text {
    width: 40%;
  }

  .google-map {
    width: 55%;
    height: 600px;
  }
}

@media (max-width: 767px) {
  .text h2 {
    font-size: 1em;
  }
}
</style>

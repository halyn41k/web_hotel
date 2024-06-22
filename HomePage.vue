<template>
  <div class="home" :class="{ loaded: isLoaded }">
    <div class="carousel">
      <img v-for="(image, index) in images" :key="index" :src="image" :class="{ active: index === currentImageIndex }" />
      <div class="carousel-text">
        <h1>Ласкаво просимо до готелю <span class="hotel-name">AMETHYST</span>!</h1>
        <p>Готель "Аметист" - це сучасний, елегантний готель, розташований у самому серці міста. Готель пропонує розкішні номери, чудовий сервіс та затишну атмосферу, де кожен гість почувається особливим. Вибираючи готель "Аметист", ви обираєте комфорт, якість та незабутні враження.</p>
      </div>
    </div>
    <div class="cta-section">
      <h2>Забронюйте кімнату зараз!</h2>
      <div class="cta-content">
        <img src="@/assets/purple-neon-arrow-1.png" class="cta-arrow" alt="Arrow"/>
        <router-link to="/booking" class="cta-button">Забронювати</router-link>
        <img src="@/assets/purple-neon-arrow-1.png" class="cta-arrow mirrored" alt="Mirrored Arrow"/>
      </div>
    </div>
    <div class="menu-section">
      <h2>Наше меню</h2>
      <div class="menu-carousel" @touchstart="startSwipe" @touchmove="swipeMove" @touchend="endSwipe">
        <div class="menu-item" v-for="(item, index) in menuItems" :key="index" :class="{ active: index === currentMenuIndex }">
          <img :src="item.image" :alt="item.name" />
          <div class="menu-text">
            <h3>{{ item.name }}</h3>
            <p>{{ item.price }} грн</p>
          </div>
        </div>
        <div class="menu-dots">
          <span v-for="(item, index) in menuItems" :key="index" :class="{ active: index === currentMenuIndex }" @click="setCurrentMenuIndex(index)"></span>
        </div>
      </div>
    </div>
    <div class="reviews-section">
      <h2>Відгуки клієнтів</h2>
      <div class="reviews-container">
        <img src="@/assets/324234.png" class="reviews-image left" />
        <div class="reviews">
          <div class="review" v-for="(review, index) in reviews" :key="index" @mouseover="animateReview" @mouseleave="stopAnimateReview">
            <div class="review-header">
              <h3>{{ review.name }}</h3>
              <div class="stars">
                <span v-for="star in 5" :key="star" class="star" :class="{ filled: star <= review.rating }">★</span>
              </div>
            </div>
            <p>{{ review.text }}</p>
          </div>
        </div>
        <img src="@/assets/324234.png" class="reviews-image right" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home',
  data() {
    return {
      images: [
        require('@/assets/extravagant-pool-party-luxurious-mansion-complete-with-vip-cabanas-dj-booth (1).jpg'),
        require('@/assets/extravagant-pool-party-luxurious-mansion-complete-with-vip-cabanas-dj-booth.jpg'),
        require('@/assets/pexels-fotoaibe-1743231.jpg')
      ],
      currentImageIndex: 0,
      isLoaded: false, // Додайте стан для відстеження завантаження
      currentMenuIndex: 0,
      reviews: [
        {
          name: 'Анна',
          rating: 5,
          text: 'Чудовий готель з відмінним обслуговуванням! Обов\'язково повернемося.'
        },
        {
          name: 'Олег',
          rating: 4,
          text: 'Комфортні номери і смачний сніданок. Рекомендуємо!'
        },
        {
          name: 'Марія',
          rating: 5,
          text: 'Незабутній досвід! Дякуємо всій команді готелю "Аметист".'
        }
      ],
      menuItems: [
        {
          name: 'Салат Цезар',
          price: 150,
          image: require('@/assets/caesar-salad.jpg')
        },
        {
          name: 'Борщ український',
          price: 120,
          image: require('@/assets/borshch.jpg')
        },
        {
          name: 'Сирники з медом',
          price: 100,
          image: require('@/assets/syrnyky.jpg')
        }
      ],
      startX: 0,
      endX: 0
    };
  },
  mounted() {
    this.startCarousel();
    this.startMenuCarousel();
    this.isLoaded = true; // Встановіть isLoaded на true після завантаження
  },
  methods: {
    startCarousel() {
      setInterval(() => {
        this.currentImageIndex = (this.currentImageIndex + 1) % this.images.length;
      }, 4000); // змінювати зображення кожні 4 секунди
    },
    startMenuCarousel() {
      setInterval(() => {
        this.currentMenuIndex = (this.currentMenuIndex + 1) % this.menuItems.length;
      }, 4000); // змінювати меню кожні 4 секунди
    },
    setCurrentMenuIndex(index) {
      this.currentMenuIndex = index;
    },
    startSwipe(event) {
      this.startX = event.touches[0].clientX;
    },
    swipeMove(event) {
      this.endX = event.touches[0].clientX;
    },
    endSwipe() {
      if (this.startX > this.endX + 50) {
        this.currentMenuIndex = (this.currentMenuIndex + 1) % this.menuItems.length;
      } else if (this.startX < this.endX - 50) {
        this.currentMenuIndex = (this.currentMenuIndex - 1 + this.menuItems.length) % this.menuItems.length;
      }
    },
    animateReview(event) {
      event.currentTarget.style.transform = 'scale(1.05)';
      event.currentTarget.style.boxShadow = '0 0 20px rgba(0, 0, 0, 0.2)';
    },
    stopAnimateReview(event) {
      event.currentTarget.style.transform = 'scale(1)';
      event.currentTarget.style.boxShadow = 'none';
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Voltaire&display=swap');

.home {
  text-align: auto;
  padding: 0%;
  opacity: 0; /* Початкове значення прозорості для плавної анімації */
  transition: opacity 1s ease; /* Анімація для зміни прозорості */
}

.home.loaded {
  opacity: 1; /* Встановіть повну прозорість після завантаження */
}

.carousel {
  position: relative;
  width: 100%;
  height: 700px; /* можна змінити висоту під ваші потреби */
  overflow: hidden;
}

.carousel img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0;
  transition: opacity 3s ease-in-out; /* Збільшення тривалості переходу */
}

.carousel img.active {
  opacity: 1;
}

.carousel-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #fff;
  text-align: center;
  background-color: rgba(0, 0, 0, 0.7); /* Збільшити прозорість темного фону */
  padding: 20px;
  border-radius: 10px;
  margin-top: 10px; /* Відступ 10 пікселів від хедера */
}

h1 {
  color: #fff;
  font-family: 'Gabriela', sans-serif; /* Використовуємо шрифт Gabriela */
}

.hotel-name {
  font-family: 'Voltaire', sans-serif; /* Використовуємо шрифт Voltaire для Amethyst */
  color: #fff;
}

p {
  color: #fff;
  font-family: 'Gabriela', sans-serif; /* Використовуємо шрифт Gabriela */
  font-size: 1.2em; /* Збільшити розмір шрифту */
}

.cta-section {
  text-align: center;
  padding: 50px 20px;
  background-color: #f8f8f8;
}

.cta-section h2 {
  font-family: 'Gabriela', sans-serif;
  font-size: 2em;
  margin-bottom: 20px;
  color: #6a0dad;
}

.cta-content {
  display: flex;
  align-items: center;
  justify-content: center;
}

.cta-button {
  font-family: 'Gabriela', sans-serif;
  font-size: 1.5em;
  background-color: #e67e22;
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
  text-decoration: none;
  transition: background-color 0.3s;
  margin: 0 10px;
}

.cta-button:hover {
  background-color: #d35400;
}

.cta-arrow {
  width: 50px;
  height: auto;
  animation: blink 1.5s infinite; /* Додати анімацію миготіння */
}

.mirrored {
  transform: scaleX(-1);
}

@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}

.menu-section {
  padding: 50px 20px;
  background-color: #fff;
  text-align: center;
}

.menu-section h2 {
  font-family: 'Gabriela', sans-serif;
  font-size: 2em;
  margin-bottom: 20px;
  color: #6a0dad;
}

.menu-carousel {
  position: relative;
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
  overflow: hidden;
  touch-action: pan-y;
}

.menu-item {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 1s ease-in-out; /* Додати плавний перехід */
}

.menu-item.active {
  opacity: 1;
  position: relative;
}

.menu-item img {
  width: 100%;
  max-height: 500px;
  object-fit: cover;
  border-radius: 10px;
}

.menu-text {
  margin-top: 10px;
}

.menu-text h3 {
  font-family: 'Gabriela', sans-serif;
  color: #6a0dad;
  margin: 10px 0 5px 0;
}

.menu-text p {
  font-family: 'Gabriela', sans-serif;
  font-size: 1.2em;
  color: #333;
}

.menu-dots {
  display: flex;
  justify-content: center;
  margin-top: 10px;
}

.menu-dots span {
  display: inline-block;
  width: 10px;
  height: 10px;
  background-color: #ddd;
  border-radius: 50%;
  margin: 0 5px;
  cursor: pointer;
}

menu-dots span.active {
  background-color: #6a0dad;
}

.reviews-section {
  padding: 50px 20px;
  background-color: #fff;
}

.reviews-section h2 {
  font-family: 'Gabriela', sans-serif;
  font-size: 2em;
  margin-bottom: 20px;
  text-align: center;
  color: #6a0dad;
}

.reviews-container {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.reviews-image {
  width: 20%;
  height: auto;
  min-height: 500px;
  object-fit: cover;
}

.reviews-image.left {
  transform: scaleX(-1);
}

.reviews {
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 600px;
  width: 100%;
  margin: 0 20px;
}

.review {
  background-color: #f8f8f8;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 20px;
  width: 100%;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.review-header h3 {
  font-family: 'Gabriela', sans-serif;
  margin: 0;
  color: #6a0dad;
}

.stars {
  display: flex;
}

.star {
  font-size: 1.5em;
  color: #ffd700; /* Gold color for stars */
}

.star.filled {
  color: #ffd700;
}

.star:not(.filled) {
  color: #ddd;
}

.review p {
  font-family: 'Gabriela', sans-serif;
  font-size: 1.2em;
  color: #333;
}

/* Media Queries for Responsive Design */

@media (max-width: 768px) {
  .carousel {
    height: 400px;
  }

  .carousel-text {
    width: 80%;
    padding: 10px;
  }

  h1 {
    font-size: 1.5em;
  }

  p {
    font-size: 1em;
  }

  .cta-section {
    padding: 30px 10px;
  }

  .cta-section h2 {
    font-size: 1.5em;
  }

  .cta-button {
    font-size: 1.2em;
    padding: 8px 15px;
  }

  .cta-arrow {
    width: 30px;
  }

  .menu-section {
    padding: 30px 10px;
  }

  .menu-section h2 {
    font-size: 1.5em;
  }

  .menu-item img {
    max-height: 300px;
  }

  .reviews-section {
    padding: 30px 10px;
  }

  .reviews-section h2 {
    font-size: 1.5em;
  }

  .reviews-image {
    width: 15%;
  }

  .review p {
    font-size: 1em;
  }
}

@media (max-width: 480px) {
  .carousel {
    height: 300px;
  }

  .carousel-text {
    width: 90%;
    padding: 5px;
  }

  h1 {
    font-size: 1.2em;
  }

  p {
    font-size: 0.9em;
  }

  .cta-section {
    padding: 20px 5px;
  }

  .cta-section h2 {
    font-size: 1.2em;
  }

  .cta-button {
    font-size: 1em;
    padding: 5px 10px;
  }

  .cta-arrow {
    width: 20px;
  }

  .menu-section {
    padding: 20px 5px;
  }

  .menu-section h2 {
    font-size: 1.2em;
  }

  .menu-item img {
    max-height: 200px;
  }

  .reviews-section {
    padding: 20px 5px;
  }

  .reviews-section h2 {
    font-size: 1.2em;
  }

  .reviews-image {
    width: 10%;
  }

  .review p {
    font-size: 0.9em;
  }
}
</style>


<template>
  <div class="contact">
    <div class="about-us">
      <h1>Про нас</h1>
      <p>Готель "Аметист" - це сучасний, елегантний готель, розташований у самому серці міста. Ми пропонуємо розкішні номери, чудовий сервіс та затишну атмосферу, де кожен гість почувається особливим.</p>
      <p>Наша мета - забезпечити вам комфортний та незабутній відпочинок, незалежно від того, чи приїхали ви до нас у справах чи на відпочинок. Ми дбаємо про ваш комфорт і забезпечуємо високий рівень обслуговування.</p>
      <p>У нашому готелі ви знайдете все необхідне для ідеального відпочинку: сучасні зручності, вишукану кухню та привітний персонал, готовий допомогти у будь-який момент. Вибираючи готель "Аметист", ви обираєте якість та комфорт.</p>
      <img :src="images.leftImage" alt="Hotel Image" class="left-image" />
      <img :src="images.rightImage" alt="Hotel Image" class="right-image" />
    </div>
    <div class="our-history">
      <h2>Наша історія</h2>
      <p>Готель "Аметист" був відкритий у 2010 році, і з тих пір ми завоювали репутацію одного з найкращих готелів у місті. Протягом років ми постійно покращували наші послуги та розширювали спектр наших зручностей, щоб задовольнити потреби наших гостей.</p>
      <p>За ці роки ми прийняли тисячі гостей з усього світу, багато з яких повертаються до нас знову і знову. Ми пишаємося тим, що можемо забезпечити високий рівень обслуговування та створювати незабутні враження для кожного гостя.</p>
    </div>
    <div class="founding-story">
      <h2>Історія заснування</h2>
      <p>Ідея створення готелю "Аметист" належить нашому засновнику, який мав на меті створити місце, де кожен гість відчуватиме себе як вдома. Натхненний красою та багатством міста, він вирішив створити готель, який би відображав ці цінності.</p>
      <p>Завдяки його візії та відданості справі, готель "Аметист" швидко став популярним місцем для проживання як серед туристів, так і серед ділових мандрівників. Ми продовжуємо розвиватися та вдосконалюватися, дотримуючись принципів, закладених нашим засновником.</p>
    </div>
    <div class="contact-form-section">
      <h2>Надішліть нам свої побажання</h2>
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="name">Ім'я</label>
          <input type="text" id="name" v-model="form.name" required />
        </div>
        <div class="form-group">
          <label for="email">Електронна пошта</label>
          <input type="email" id="email" v-model="form.email" required />
        </div>
        <div class="form-group">
          <label for="message">Повідомлення</label>
          <textarea id="message" v-model="form.message" required></textarea>
        </div>
        <button type="submit" class="submit-button">Надіслати</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AboutUs',
  data() {
    return {
      form: {
        name: '',
        email: '',
        message: ''
      },
      images: {
        leftImage: '',
        rightImage: ''
      }
    };
  },
  methods: {
    async submitForm() {
      try {
        const response = await fetch('http://localhost/new-hotel-website/backend/submit_wish.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(this.form)
        });

        const result = await response.json();

        if (result.success) {
          alert('Дякуємо за ваше повідомлення! Ми зв\'яжемося з вами найближчим часом.');
          this.form.name = '';
          this.form.email = '';
          this.form.message = '';
        } else {
          alert('Error: ' + result.error);
        }
      } catch (error) {
        alert('An error occurred while submitting the form.');
        console.error(error);
      }
    },
    async fetchImages() {
      try {
        const response = await fetch('http://localhost/new-hotel-website/backend/get_images.php');
        if (!response.ok) {
          throw new Error('Network response was not ok: ' + response.statusText);
        }
        const images = await response.json();

        console.log('Fetched images:', images); // Log fetched images

        const leftImage = images.find(img => img.category === 'aboutus' && img.image_name === 'aboutus1.png');
        const rightImage = images.find(img => img.category === 'aboutus' && img.image_name === 'aboutus2.png');

        if (leftImage) {
          this.images.leftImage = this.getImageUrl(leftImage.image_name);
        }
        if (rightImage) {
          this.images.rightImage = this.getImageUrl(rightImage.image_name);
        }
      } catch (error) {
        console.error('Error fetching images:', error);
      }
    },
    getImageUrl(imageName) {
      return `http://localhost/new-hotel-website/src/assets/${imageName}`;
    }
  },
  mounted() {
    document.title = 'Amethyst Hotel | About Us';
    this.fetchImages();
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Gabriela&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Voltaire&display=swap');

.contact {
  padding: 20px;
  max-width: 800px;
  margin: 30px auto 0 auto; /* Додано відступ від верха сторінки */
  position: relative;
}

.about-us, .our-history, .founding-story {
  margin-bottom: 50px;
}

.about-us h1, .our-history h2, .founding-story h2 {
  font-family: 'Gabriela', sans-serif;
  color: #6a0dad;
  text-align: center;
}

.about-us h1 {
  font-size: 2.5em;
  margin-bottom: 20px;
}

.our-history h2, .founding-story h2 {
  font-size: 2em;
  margin-bottom: 20px;
}

.about-us p, .our-history p, .founding-story p {
  font-family: 'Gabriela', sans-serif;
  font-size: 1.2em;
  color: #333;
  line-height: 1.6;
  margin-bottom: 20px;
}

.left-image, .right-image {
  position: fixed;
  height: 100%;
  width: auto;
  opacity: 0.5;
  z-index: -1;
}

.left-image {
  left: -150px; /* Зміна позиції лівого зображення лівіше */
  top: 0;
}

.right-image {
  right: -150px; /* Зміна позиції правого зображення правіше */
  top: 0;
}

.contact-form-section {
  background-color: #f8f8f8;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.contact-form-section h2 {
  font-family: 'Gabriela', sans-serif;
  font-size: 2em;
  color: #6a0dad;
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  font-family: 'Gabriela', sans-serif;
  font-size: 1.2em;
  color: #333;
  display: block;
  margin-bottom: 5px;
}

.form-group input, .form-group textarea {
  width: 100%;
  padding: 3px;
  font-family: 'Gabriela', sans-serif;
  font-size: 1em;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.form-group textarea {
  height: 100px;
}

.submit-button {
  font-family: 'Gabriela', sans-serif;
  font-size: 1.5em;
  background-color: #e67e22;
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.3s;
}

.submit-button:hover {
  background-color: #d35400;
}

/* Media Queries for Responsive Design */

@media (max-width: 768px) {
  .contact {
    padding: 15px;
    margin-top: 50px; /* Зміна відступу від верха сторінки для середніх екранів */
  }

  .about-us h1 {
    font-size: 2em;
  }

  .our-history h2, .founding-story h2 {
    font-size: 1.8em;
  }

  .about-us p, .our-history p, .founding-story p {
    font-size: 1em;
  }

  .left-image, .right-image {
    display: none;
  }

  .contact-form-section h2 {
    font-size: 1.8em;
  }

  .form-group input, .form-group textarea {
    font-size: 0.9em;
  }

  .submit-button {
    font-size: 1.2em;
  }
}

@media (max-width: 480px) {
  .contact {
    padding: 10px;
    margin-top: 30px; /* Зміна відступу від верха сторінки для малих екранів */
  }

  .about-us h1 {
    font-size: 1.8em;
  }

  .our-history h2, .founding-story h2 {
    font-size: 1.5em;
  }

  .about-us p, .our-history p, .founding-story p {
    font-size: 0.9em;
  }

  .contact-form-section h2 {
    font-size: 1.5em;
  }

  .form-group input, .form-group textarea {
    font-size: 0.8em;
  }

  .submit-button {
    font-size: 1em;
  }
}
</style>

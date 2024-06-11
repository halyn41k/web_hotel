import { createApp } from 'vue';
import App from './App.vue';  // Шлях до App.vue
import router from './index.js';  // Шлях до index.js

createApp(App).use(router).mount('#app');

<template>
  <div id="app">
    <Header />
    <router-view></router-view>
    <Loader v-if="isLoading" /> 
    <Footer />
  </div>
</template>

<script>
import axios from 'axios';
import Header from '@/components/AppHeader.vue';
import Footer from '@/components/Footer.vue';
import Loader from '@/components/Loader.vue';

export default {
  name: 'App',
  components: {
    Header,
    Footer,
    Loader
  },
  data() {
    return {
      isLoading: false,
      data: [] 
    };
  },
  methods: {
    async fetchData() {
      this.isLoading = true;
      try {
        const response = await axios.get('http://localhost:3000/api/data');
        this.data = response.data;
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        this.isLoading = false;
      }
    }
  },
  mounted() {
    this.fetchData(); 
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  position: relative;
  min-height: 100vh;
}

::-webkit-scrollbar {
  width: 8px; 
}

::-webkit-scrollbar-track {
  background: #f1f1f1; 
}

::-webkit-scrollbar-thumb {
  background: #888; 
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555; 
}

* {
  scrollbar-width: thin; 
  scrollbar-color: #888 #f1f1f1; 
}
</style>

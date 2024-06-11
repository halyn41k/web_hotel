// Імпортуємо необхідні функції з Vue Router
import { createRouter, createWebHistory } from 'vue-router';

// Імпортуємо компоненти
import HomePage from './components/HomePage.vue';
import RoomList from './components/RoomList.vue';
import BookingForm from './components/BookingForm.vue';
import Registration from './components/Registration.vue';
import UserDashboard from './components/UserDashboard.vue';
import Login from './components/Login.vue';
import Admin from './components/Admin.vue';
import Location from './components/Location.vue';
import Contact from './components/Contact.vue';
import Loader from './components/Loader.vue';

// Визначаємо маршрути
const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage
  },
  {
    path: '/rooms',
    name: 'RoomList',
    component: RoomList
  },
  { 
    path: '/booking',
    name: 'Booking',
    component: BookingForm
  },
  {
    path: '/registration',
    name: 'Registration',
    component: Registration
  },
  {
    path: '/user-dashboard',
    name: 'UserDashboard',
    component: UserDashboard
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/admin',
    name: 'Admin',
    component: Admin
  },
  {
    path: '/location',
    name: 'Location',
    component: Location
  },
  {
    path: '/contacts',
    name: 'Contact',
    component: Contact
  },
  {
    path: '/loader',
    name: 'Loader',
    component: Loader
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

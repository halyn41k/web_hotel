<<<<<<< HEAD
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
=======
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/components/HomePage.vue')
  },
  {
    path: '/rooms',
    name: 'RoomList',
    component: () => import('@/components/RoomList.vue')
  },
  { 
    path: '/booking',
    name: 'Booking',
    component: () => import('@/components/BookingForm.vue')
  },
  {
    path: '/registration',
    name: 'Registration',
    component: () => import('@/components/Registration.vue')
  },
  {
    path: '/user',
    name: 'UserDashboard',
    component: () => import('@/components/UserDashboard.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/components/Login.vue')
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: () => import('@/components/Admin.vue')
  },
  {
    path: '/location',
    name: 'Location',
    component: () => import('@/components/Location.vue')
  },
  {
    path: '/contacts',
    name: 'Contact',
    component: () => import('@/components/Contact.vue')
  },
  {
    path: '/loader',
    name: 'Loader',
    component: () => import('@/components/Loader.vue')
  },
  {
    path: '/payment',
    name: 'Payment',
    component: () => import('@/components/Payment.vue')
  }
  
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
>>>>>>> database

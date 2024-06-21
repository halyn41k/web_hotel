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

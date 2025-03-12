import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/auth/Login.vue';
import Register from '../pages/auth/Register.vue';
import RegisterSuccess from '../pages/auth/RegisterSuccess.vue';
import Panel from '../pages/auth/Panel.vue';

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/cadastro', component: Register },
  { path: '/sucesso-cadastro', component: RegisterSuccess },
  { path: '/painel', component: Panel },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

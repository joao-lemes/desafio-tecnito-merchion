<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const email = ref('');
const password = ref('');
const error = ref<string | null>(null);

const login = async () => {
  error.value = null;
  try {
    const response = await axios.post('/api/login', {
      email: email.value,
      password: password.value
    });

    localStorage.setItem('token', response.data.access_token);

    router.push('/painel');
  } catch (err) {
    error.value = 'Erro ao fazer login.';
    if (err.response && err.response.data && err.response.data.message) {
      error.value = err.response.data.message;
    }
  }
};
</script>

<template>
  <div class="container">
      <h1>Login</h1>
      <form @submit.prevent="login" class="space-y-4">
        <input type="email" v-model="email" placeholder="E-mail" required class="border p-2 rounded w-full" />
        <input type="password" v-model="password" placeholder="Senha" required class="border p-2 rounded w-full" />
        <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700 w-full">Login</button>
        <router-link to="/cadastro" class="btn">Registrar</router-link>
      </form>
      <p v-if="error" class="error text-red-500 mt-2">{{ error }}</p>
  </div>
</template>

<style scoped>
.container {
  max-width: 450px;
  margin: auto;
  padding: 2rem;
  text-align: center;
  background-color: #FFF;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

input, button {
  display: block;
  width: 100%;
  margin: 10px 0;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #CCC;
}

button {
  padding: 8px 16px;
  max-width: 105px;
  margin-left: 172px;
  background-color: #F44336;
  color: #FFF;
  font-size: 1.2rem;
  cursor: pointer;
}

button:hover {
  background-color: #D32F2F;
}

.btn {
  display: inline-block;
  padding: 8px 16px;
  font-size: 1.2rem;
  color: #FFF;
  background: #F44336;
  text-decoration: none;
  border-radius: 5px;
}

.btn:hover {
  background: #D32F2F;
}
</style>

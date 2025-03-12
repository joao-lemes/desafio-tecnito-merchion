<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const error = ref<string | null>(null);

const register = async () => {
  error.value = null;
  try {
    await axios.post('/api/users', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    });
    router.push('/sucesso-cadastro');
  } catch (err) {
    error.value = err.response?.data?.message || 'Erro ao cadastrar. Tente novamente.'
    error.value = 'Erro ao cadastrar. Tente novamente.';
    if (err.response && err.response.data && err.response.data.message) {
      error.value = err.response.data.message;
    }
  }
};
</script>

<template>
    <div class="container">
      <h1 class="text-2x1 font-bold text-gray-800">Cadastro</h1>
      <form @submit.prevent="register" class="space-y-4">
        <input type="text" v-model="name" placeholder="Nome" required class="border p-2 rounded w-full" />
        <input type="email" v-model="email" placeholder="E-mail" required class="border p-2 rounded w-full" />
        <input type="password" v-model="password" placeholder="Senha" required class="border p-2 rounded w-full" />
        <input type="password" v-model="passwordConfirmation" placeholder="Confirme a sua senha" required class="border p-2 rounded w-full" />
        <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700 w-full">Cadastrar</button>
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
  background-color: #F44336;
  color: #FFF;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  background-color: #D32F2F;
}
</style>

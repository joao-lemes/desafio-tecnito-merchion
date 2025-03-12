<template>
  <div class="container">
    <h1>Parabéns!</h1>
    <p>Cadastro realizado com sucesso.</p>
    <router-link to="/login" class="btn">Ir para o login</router-link>
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

h1 {
  font-size: 2rem;
  color: #E63946;
}

p {
  font-size: 1.2rem;
  margin: 10px 0;
}

.btn {
  display: inline-block;
  margin-top: 20px;
  padding: 12px 24px;
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

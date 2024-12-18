<template>
    <div id="app">
      <div v-if="user">
        <h1>Olá {{ user.name }}, selecione a mesa e depois o horário disponível para efetuar a reserva</h1> 
        <h3>Funcionamento de Segunda a Sábado das 18h as 00h com horários especiais no domingo</h3>

        <!-- Logout Button -->
        <div class="logout-container">
          <button @click="logout">Logout</button>
        </div>

        <TablesScreen />

        <div v-if="user && user.admin === 1">
          <TablesAdminVue />
        </div>
      </div>
      <div v-else>
        <h1>Bem-vindo ao Reserve APP - Acesse ou registre-se para realizar sua reserva</h1>
        <div class="auth-container">
          <!-- Login Button -->
          <button @click="openModal('login')">Login</button>

          <!-- Register Button -->
          <button @click="openModal('register')">Register</button>
        </div>

        <!-- Modal for Login -->
        <div v-if="isModalOpen && modalType === 'login'" class="modal">
          <div class="modal-content">
            <h2>Login</h2>
            <form @submit.prevent="submitForm">
              <div>
                <label for="email">Email:</label>
                <input v-model="form.email" type="email" id="email" required />
              </div>
              <div>
                <label for="password">Password:</label>
                <input v-model="form.password" type="password" id="password" required />
              </div>
              <button type="submit">Login</button>
              <button type="button" @click="closeModal">Close</button>
            </form>
          </div>
        </div>

        <!-- Modal for Register -->
        <div v-if="isModalOpen && modalType === 'register'" class="modal">
          <div class="modal-content">
            <h2>Register</h2>
            <form @submit.prevent="submitForm">
              <div>
                <label for="name">Nome:</label>
                <input v-model="form.name" type="string" id="name" required />
              </div>
              <div>
                <label for="email">Email:</label>
                <input v-model="form.email" type="email" id="email" required />
              </div>
              <div>
                <label for="password">Password:</label>
                <input v-model="form.password" type="password" id="password" required />
              </div>
              <button type="submit">Register</button>
              <button type="button" @click="closeModal">Close</button>
            </form>
          </div>
        </div>
    </div>
    </div>
</template>
  
<script>
import axios from 'axios';
axios.defaults.baseURL = 'http://localhost';
axios.defaults.withCredentials = true;

import TablesScreen from './components/TablesComponent.vue';
import TablesAdminVue from './components/admin/TablesAdminComponent.vue';

export default {
  name: "App",
  data() {
    return {
      user: null,
      form: {
        name: '',
        email: '',
        password: ''
      },
      isModalOpen: false,
      modalType: '', 
    };
  },

  methods: {
    async fetchUser() {
      try {
        const response = await axios.get('/api/user');
      } catch (error) {
        console.error("Usuário não autenticado", error);
        this.user = null;
        localStorage.removeItem('user');
        localStorage.removeItem('token');
      }
    },
    openModal(type) {
      this.modalType = type; 
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
      this.form = { name: '', email: '', password: '' };
    },
    async submitForm() {
      try {
        await axios.get('/sanctum/csrf-cookie');

        const response = this.modalType === 'register'
          ? await axios.post('/api/register', this.form) 
          : await axios.post('/api/login', this.form);

        const { user, token } = response.data;

        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('token', token);

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        this.user = user;
        this.closeModal();
      } catch (error) {
        console.error("Erro ao enviar formulário:", error);
        alert('Um erro aconteceu, por favor tente novamente.');
      }
    },
    async logout() {
      try {
        await axios.post('/api/logout');
        window.location.href = '/';
        alert('Até a próxima!');
      } catch (error) {
        console.error('Erro de logout:', error);
        alert('Ops, um erro aconteceu');
      }
    }
  },

  mounted() {
    const storedUser = localStorage.getItem('user');
    const token = localStorage.getItem('token');

    if (storedUser && token) {
      this.user = JSON.parse(storedUser);
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }

    this.fetchUser();
  },

  components: {
    TablesScreen,
    TablesAdminVue
  },
};
</script>
  
  <style scoped>
  h1 {
    color: #42b983;
    text-align: center;
  }

  h3 {
    text-align: center;
  }
  
  button {
    padding: 10px 20px;
    background-color: #42b983;
    color: white;
    border: none;
    border-radius: 4px;
    margin: 10px;
  }
  
  button:hover {
    background-color: #36a16a;
  }
  
  /* Modal styles */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
  }
  
  .modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 4px;
    width: 300px;
    text-align: center;
  }
  
  .modal-content input {
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .modal-content button {
    padding: 10px;
    background-color: #42b983;
    color: white;
    border: none;
    border-radius: 4px;
    margin-top: 10px;
  }
  
  .modal-content button:hover {
    background-color: #36a16a;
  }

  .logout-container {
    display: flex;
    justify-content: right;
    align-items: center;
    text-align: center; 
  }

  .auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center; 
  }
  </style>
  
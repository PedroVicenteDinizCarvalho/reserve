<template>
    <div id="app">
      <div v-if="user">
        <p>Hello, {{ user.name }}!</p>  <!-- Display the user's name -->
        <TablesScreen />

        <div v-if="user.admin = true">
          <TablesAdminVue />
        </div>
      </div>
      <div v-else>
        <h1>Bem-vindo ao Reserve APP - Acesse ou registre-se para realizar sua reserva</h1>
        <div>
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
  import TablesScreen from './components/TablesComponent.vue';
  import TablesAdminVue from './components/admin/TablesAdminComponent.vue';


  export default {
    name: "App",
    data() {
      return {
        user: null,
        form: {
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
          this.user = response.data;
        } catch (error) {
          console.error("Error fetching user data:", error);
        }
      },
      openModal(type) {
        this.modalType = type;  // 'login' or 'register'
        this.isModalOpen = true;  // Open modal
      },
      closeModal() {
        this.isModalOpen = false;  // Close modal
        this.form.email = '';
        this.form.password = '';
      },
    },
    async submitForm() {
      try {
        const response = this.modalType === 'register'
          ? await axios.post('/api/register', this.form)  // Register user
          : await axios.post('/api/login', this.form);    // Login user

        this.user = response.data;
        this.closeModal();
      } catch (error) {
        console.error("Error submitting form:", error);
        alert('An error occurred. Please try again.');
      }
    },
    mounted() {
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
  </style>
  
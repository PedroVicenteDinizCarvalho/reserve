<template>
    <div id="app">
      <div v-if="user">
        <p>Hello, {{ user.name }}!</p>  <!-- Display the user's name -->
        <TablesScreen />
        <TablesAdminVue />
      </div>
      <div v-else="user"> 
        'TESTE'
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
        user: null
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
  
  <style>
  h1 {
    color: #42b983;
  }
  </style>
  
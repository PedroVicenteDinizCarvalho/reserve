<template>
    <div>
      <h1>Manage Tables</h1>
      <form @submit.prevent="addTable">
        <div>
          <label>Description:</label>
          <input v-model="form.description" type="text" required />
        </div>
        <div>
          <label>Number of Chairs:</label>
          <input v-model="form.number_chairs" type="number" required />
        </div>
        <button type="submit">Add Table</button>
      </form>
  
      <table border="1">
        <thead>
          <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Number of Chairs</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="table in tables" :key="table.id">
            <td>{{ table.id }}</td>
            <td>
              <input
                v-model="table.description"
                @change="updateTable(table)"
              />
            </td>
            <td>
              <input
                v-model="table.number_chairs"
                type="number"
                @change="updateTable(table)"
              />
            </td>
            <td>
              <button @click="deleteTable(table.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        tables: [],
        form: {
          description: '',
          number_chairs: 0,
        },
      };
    },
    methods: {
      async fetchTables() {
        const response = await axios.get('/api/tables');
        this.tables = response.data;
      },
      async addTable() {
        const response = await axios.post('/api/tables', this.form);
        this.tables.push(response.data);
        this.form.description = '';
        this.form.number_chairs = 0;
      },
      async updateTable(table) {
        await axios.put(`/api/tables/${table.id}`, {
          description: table.description,
          number_chairs: table.number_chairs,
        });
      },
      async deleteTable(id) {
        await axios.delete(`/api/tables/${id}`);
        this.tables = this.tables.filter((table) => table.id !== id);
      },
    },
    mounted() {
      this.fetchTables();
    },
  };
  </script>
  
  <style>
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  table th, table td {
    padding: 8px;
    text-align: left;
  }
  
  form {
    margin-bottom: 20px;
  }
  </style>
  
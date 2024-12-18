<template>
  <div class="tables-screen">
    <h1>Mesas do Restaurante</h1>
    <div class="tables-grid">
      <div
        v-for="table in tables"
        :key="table.id"
        :class="['table', getTableStatus(table.id)]"
        @click="reserveTable(table.id)"
      >
        <p>Mesa {{ table.id }}</p>
        <p>{{ table.number_chairs }} lugares</p>
        <span>{{ getTableStatus(table.id) }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: "TablesScreen",
  data() {
    return {
      tables: [],
      reservations: [],
      user: null,
    };
  },
  methods: {
    async fetchTablesAndReservations() {
      try {
        const tablesResponse = await axios.get('/api/tables');
        const reservationsResponse = await axios.get('/api/reservations');
        this.tables = tablesResponse.data;
        this.reservations = reservationsResponse.data;

        const userResponse = await axios.get('/api/user');
        this.user = userResponse.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    getTableStatus(tableId) {
      const today = new Date().toISOString().split('T')[0];
      const currentHour = new Date().toTimeString().split(' ')[0];
      const reservation = this.reservations.find(
        (r) =>
          r.table_id === tableId &&
          r.date === today &&
          r.start_hour <= currentHour &&
          r.end_hour >= currentHour
      );
      return reservation ? "reservada" : "disponivel";
    },
    async reserveTable(tableId) {
      if (!this.user) {
        alert("You need to be logged in to make a reservation.");
        return;
      }

      const date = prompt("Informe a data da reserva (YYYY-MM-DD):");
      const startHour = prompt("Informe o horário que deseja chegar (HH:MM):");
      const endHour = prompt("Informe o horário que pretende deixar a mesa (HH:MM):");

      try {
        const response = await axios.post('/api/reservations', {
          table_id: tableId,
          user_id: this.user.id,
          date,
          start_hour: startHour,
          end_hour: endHour,
        });
        alert("Mesa reservada!");
        this.reservations.push(response.data);
      } catch (error) {
        console.error("Error reserving table:", error.response.data);
        alert(error.response.data.error || "Erro na reserva da mesa");
      }
    },
  },
  async mounted() {
    await this.fetchTablesAndReservations();
  },
};
</script>

  
  <style scoped>
  .tables-screen {
    text-align: center;
    font-family: Arial, sans-serif;
  }
  
  .tables-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    padding: 1rem;
  }
  
  .table {
    padding: 1rem;
    border: 2px solid #ccc;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
  }
  
  .table.disponivel {
    background-color: #e7f9e7;
    border-color: #28a745;
  }
  
  .table.ocupada {
    background-color: #ffe7e7;
    border-color: #dc3545;
  }
  
  .table.reservada {
    background-color: #fff3cd;
    border-color: #ffc107;
  }
  
  .table:hover {
    transform: scale(1.05);
  }
  </style>
  
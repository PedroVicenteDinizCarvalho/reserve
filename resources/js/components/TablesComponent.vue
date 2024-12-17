<template>
    <div class="tables-screen">
      <h1>Restaurant Tables</h1>
      <div class="tables-grid">
        <div
          v-for="table in tables"
          :key="table.id"
          :class="['table', table.status]"
          @click="toggleTableStatus(table.id)"
        >
          <p>Table {{ table.id }}</p>
          <span>{{ table.status }}</span>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "TablesScreen",
    data() {
      return {
        tables: Array.from({ length: 15 }, (_, i) => ({
          id: i + 1,
          status: "available", // Available, occupied, or reserved
        })),
      };
    },
    methods: {
      toggleTableStatus(id) {
        const table = this.tables.find((t) => t.id === id);
        if (table.status === "available") {
          table.status = "occupied";
        } else if (table.status === "occupied") {
          table.status = "reserved";
        } else {
          table.status = "available";
        }
      },
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
  
  .table.available {
    background-color: #e7f9e7;
    border-color: #28a745;
  }
  
  .table.occupied {
    background-color: #ffe7e7;
    border-color: #dc3545;
  }
  
  .table.reserved {
    background-color: #fff3cd;
    border-color: #ffc107;
  }
  
  .table:hover {
    transform: scale(1.05);
  }
  </style>
  
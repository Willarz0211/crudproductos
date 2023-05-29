<template>
  <div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>UPC</th>
          <th>Número de parte</th>
          <th>Marca</th>
          <th>Categorías</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="producto in productos" :key="producto.id">
          <td>{{ producto.id }}</td>
          <td>{{ producto.name }}</td>
          <td>{{ producto.upc }}</td>
          <td>{{ producto.part_number }}</td>
          <td>{{ producto.brand_id.name }}</td>
          <td>
            <ul>
              <li v-for="categoria in producto.categories" :key="categoria.name">
                {{ categoria.name }}
              </li>
            </ul>
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
        productos: []
      };
    },
    mounted() {
      this.fetchProductos();
    },
    methods: {
      fetchProductos() {
        axios.get('/api/products')
          .then(response => {
            this.productos = response.data.products;
          })
          .catch(error => {
            console.error(error);
          });
      }
    }
  };
  </script>
  
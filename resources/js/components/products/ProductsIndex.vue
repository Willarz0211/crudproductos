<template>
  <div>
    <div class="d-flex justify-content-end mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <router-link :to="{ name: 'product.create' }" class="btn btn-primary mx-2">Crear Producto</router-link>
        </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th class="text-center">ID</th>
          <th class="text-center">Nombre</th>
          <th class="text-center">UPC</th>
          <th class="text-center">Número de parte</th>
          <th class="text-center">Marca</th>
          <th class="text-center">Categorías</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
            <td class="text-center">{{ product.id }}</td>
            <td class="text-center">{{ product.name }}</td>
            <td class="text-center">{{ product.upc }}</td>
            <td class="text-center">{{ product.part_number }}</td>
            <td class="text-center">{{ product.brand_id.name }}</td>
            <td>
                <ul>
                    <li v-for="category in product.categories" :key="category.name" class="text-center">{{ category.name }}</li>
                </ul>
            </td>
            <td class="text-center">
                <router-link :to="{ name: 'product.edit', params: { id: product.id} }" class="btn btn-primary mx-2">Editar</router-link>
                <button @click="deleteProduct(product.id)" class="btn btn-danger">Eliminar</button>
            </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import useProducts from "../../composables/products";
import {onMounted} from "vue";

export default{

    setup(){
        
        const{products, getProducts, destroyProduct} = useProducts()

        onMounted(async () => {
            await getProducts();
        });


        const deleteProduct = async (id) => {
            if(!window.confirm('¿Estás seguro de eliminar este producto?')) return;
            const responseMessage = await destroyProduct(id);
            window.alert(responseMessage);
            await getProducts();
        }

        return{
            products,
            deleteProduct
        }
    }
}


</script>
import { ref } from 'vue';
// import axios from 'axios';


export default function useProducts() {
    const products = ref([]);

    const getProducts = async () => {
        let response = await axios.get('/api/products')  
        products.value = response.data.products;
    }

    const destroyProduct = async (product) => {
        let response = await axios.delete('/api/deleteProduct/' + product);
        return response.data.message;
    }

    return {
        products,
        getProducts,
        createProduct,
        destroyProduct
    }

}
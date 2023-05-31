import { ref, reactive } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';


export default function useProducts() {
    const products = ref([]);
    const product = reactive({});
    const brands = ref([]);
    const categories = reactive([]);
    const router = useRouter();
    const errors = ref('');

    const getProducts = async () => {
        let response = await axios.get('/api/products')  
        products.value = response.data.products;
    }
    
    const getProduct = async (id) => {
        let response = await axios.get('/api/product/' + id)
        Object.assign(product, response.data.product);
        product.brand_id = response.data.product.brand_id.id;
    }

    const getBrands = async () => {
        let response = await axios.get('/api/brands')
        brands.value = response.data.brands;
    }

    const getCategories = async () => {
        let response = await axios.get('/api/categories')
        categories.value = response.data.categories;
        console.log(categories.value);
    }

    const storeProduct = async (product) => {
        errors.value = '';
        try {
            let response = await axios.post('/api/storeProduct', product);
            await router.push({name: 'product.index'});
            return response.data.message;
        } catch (error) {
            console.log(error.response.data.errores);
            errors.value = error.response.data.errores;
            console.log(errors.value);
        }
    }

    const updateProduct = async (id, $requestData) => {
        errors.value = '';
        try {
            let response = await axios.put('/api/updateProduct/'+id, $requestData);
            await router.push({name: 'product.index'});
            return response.data.message;
        } catch (error) {
            console.log(error.response.data.errores);
            errors.value = error.response.data.errores;
            console.log(errors.value);
        }
    }

    const destroyProduct = async (product) => {
        try {
            let response = await axios.delete('/api/deleteProduct/' + product);
            return response.data.message;
        } catch (error) {
            return error.response.data.message;
            
        }

    }

    return {
        brands,
        categories,
        products,
        product,
        errors,
        getProducts,
        getProduct,
        getBrands,
        getCategories,
        destroyProduct,
        storeProduct,
        updateProduct
    }

}
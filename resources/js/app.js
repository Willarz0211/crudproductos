require('./bootstrap');

import { createApp } from 'vue';
import router from './router';

import ProductsIndex from './components/products/ProductsIndex.vue';

const app = createApp({
    components: {
        ProductsIndex
    }
}).use(router).mount('#app');


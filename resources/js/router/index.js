import {createRouter, createWebHistory} from 'vue-router';

import Index from '../components/products/ProductsIndex.vue';

    const routes = [
        {
            path: '/home',
            name: 'product.index',
            component: Index
        },
        {
            path: '/crate',
            name: 'product.create',
            component: Index
        }
    ];

    const router = createRouter({
        history: createWebHistory(),
        routes
      });
      
export default router;
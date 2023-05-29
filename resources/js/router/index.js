import {createRouter, createWebHistory} from 'vue-router';

import ProductIndex from '../components/products/ProductsIndex.vue';
import ProductCreate from '../components/products/ProductsCreate.vue';
import ProductEdit from '../components/products/ProductsEdit.vue';

    const routes = [
        {
            path: '/home',
            name: 'product.index',
            component: ProductIndex
        },
        {
            path: '/create',
            name: 'product.create',
            component: ProductCreate
        },
        {
            path: '/edit/:id',
            name: 'product.edit',
            component: ProductEdit
        }
    ];

    const router = createRouter({
        history: createWebHistory(),
        routes
      });
      
export default router;
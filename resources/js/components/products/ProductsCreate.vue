<template>
    <div class="errors">
        <ul class="list-unstyled">
            <li v-for="(error, field) in errors" :key="field" class="text-danger">
            {{ error }}
            </li>
        </ul>
</div>
    <form @submit.prevent="saveProduct">
        <div class="mb-3">
            <label for="name">Nombre:</label>
            <input type="text" id="name" v-model="product.name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="upc">UPC:</label>
            <input type="text" id="upc" v-model="product.upc" class="form-control">
        </div>
        <div class="mb-3">
            <label for="part_number">Número de Parte:</label>
            <input type="text" id="part_number" v-model="product.part_number" class="form-control">
        </div>
        <div class="mb-3">
        <label for="brand">Marca:</label>
            <select id="brand" v-model="product.brand_id" class="form-control">
                <option v-for="brand in brands" :value="brand.id" :key="brand.id">{{ brand.name }}</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="categories">Categorías:</label>
            <Multiselect
                v-model="product.categories"
                mode="tags"
                :close-on-select="false"
                :searchable="true"
                :create-option="true"
                :options="categories.value ? categories.value.map(category => ({ label: category.name, value: category.id })) : []"
            />
            <!-- <Select2 v-model="myValue" :options="myOptions" :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)" @select="mySelectEvent($event)" />
            <h4>Value: {{ myValue }}</h4>
            <select id="categories" v-model="product.categories " class="form-control" multiple>
                <option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</option>
            </select> -->
        </div>
        <div class="mb-3">
            <label for="images">Imágenes:</label>
            <input type="file" id="images" @change="handleImageUpload" multiple class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Crear Producto</button>
    </form>
</template>
<script>
import useProducts from "../../composables/products";
import {reactive, onBeforeMount} from "vue";
import Multiselect from '@vueform/multiselect'

export default{


    components: {
      Multiselect,
    },
    setup() {

        const{brands,categories,getBrands, getCategories, errors, storeProduct} = useProducts();

       

        onBeforeMount(async () => {
            await getBrands();
            await getCategories();
        });

        const product = reactive(
            {
                name: '',
                upc: '',
                part_number: '',
                brand_id: '',
                categories: [],
                images: [],
                brands: [],
            }
        );

        const saveProduct = async () => {
            const requestData = {
                name: product.name,
                upc: product.upc,
                part_number: product.part_number,
                brand_id: product.brand_id,
                categories: product.categories.map((category) => category),
                images: product.images.map((image) => ({
                    name: image.name,
                    file: image.file,
                })),
            };
            const responseMessage = await storeProduct(requestData);
        };

        const handleImageUpload = function(event) {
            const files = event.target.files;
            const reader = new FileReader();
            const product = this.product; 

            reader.onload = function() {
                const base64Image = reader.result;
                for (let i = 0; i < files.length; i++) {
                const image = {
                    name: files[i].name,
                    file: base64Image,
                };
                product.images.push(image);
                }
            };

            reader.readAsDataURL(files[0]);
        };

        

        return{
            brands,
            categories,
            product,
            errors,
            saveProduct,
            handleImageUpload
        }

    }, 
}

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
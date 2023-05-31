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
                :options="categories.value ? categories.value.map(category => ({ label: category.name, value: category.id})) : []"
            />
        </div>
        <div class="mb-3">
            <label for="images">Imágenes:</label>
            <input type="file" id="images" @change="handleImageUpload" multiple class="form-control">
            <div class="mt-2">
                <ul>
                    <li v-for="(image, index) in product.images" :key="index">
                        <img v-if="image.url" :src="image.file" alt="Imagen del producto" style="width: 200px; height: auto;">
                        <img v-else :src="`/product_images/${image.name}`" alt="Imagen del producto" style="width: 200px; height: auto;">
                        <button type="button" @click="removeImages(image.id)">Eliminar</button>
                    </li>
                </ul>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
    </form>
</template>
<script>
import useProducts from "../../composables/products";
import {onMounted, onBeforeMount, ref, reactive} from "vue";
import Multiselect from '@vueform/multiselect'

export default{

    props: {
        id: {
            type: String,
            required: true,
        },
    },
    components: {
      Multiselect,
    },
    setup(props) {

        const{brands, categories, product, getBrands, getCategories, errors, getProduct, updateProduct} = useProducts();
        const deletedImagesArray = reactive([]);

       

        onBeforeMount(async () => {
            await getProduct(props.id);
            await getBrands();
            await getCategories();  
        });


        const saveProduct = async () => {
            const parsedCategories = JSON.parse(JSON.stringify(product.categories));

            const hasNameParameter = parsedCategories.every(category => {
                return category.hasOwnProperty('name');
            });

            const categories = hasNameParameter ? parsedCategories.map((category) => category.id) : product.categories.map((category) => category)
            
            const requestData = {
                name: product.name,
                upc: product.upc,
                part_number: product.part_number,
                brand_id: product.brand_id,
                categories: categories,
                images: product.images.map((image) => ({
                    name: image.name,
                    file: image.file,
                })),
                deletedImages: deletedImagesArray.map((image) => ( image )),
            };
            console.log(requestData);
            const responseMessage = await updateProduct(props.id,requestData);
        };

        const handleImageUpload = function(event) {
            const files = event.target.files;
            const product = this.product; 

            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                const file = files[i];

                reader.onload = function() {
                    const base64Image = reader.result;
                    const image = {
                        name: file.name,
                        file: base64Image,
                        url: true,
                    };
                    product.images.push(image);
                };

                reader.readAsDataURL(file);
            }
        };

        const removeImages = (id) => {
            const index = product.images.findIndex((image) => image.id === id);
            if (index !== -1) {
                product.images.splice(index, 1);
                const input = document.getElementById('images');
                input.value = '';
            }
            if(id !== undefined){

                deletedImagesArray.push({id: id});
            }
        }


        

        return{
            brands,
            categories,
            product,
            errors,
            deletedImagesArray,
            removeImages,
            saveProduct,
            handleImageUpload
        }

    }, 
}

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
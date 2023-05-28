<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Image;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

//crea la clase productrepository que implementa la interfaz productinterface
class productrepository implements ProductInterface
{
    public $product;
    public $productcategory;
    public $image;
    public $brand;
    public $category;

    public function __construct(Product $product, ProductCategory $productcategory, Image $image, Brand $brand, Category $category)
    {
        $this->product = $product;
        $this->productcategory = $productcategory;
        $this->image = $image;
        $this->brand = $brand;
        $this->category = $category;
    }

    //implementa la funcion getallproducts de la interfaz productinterface
    public function getAllProducts()
    {
        // $products = $this->product->with('brand')->with('images')->with('categories')->get();
        $products = $this->product::with('brand')->with('images')->with('categories')->get();
        return $products;
    }

    public function getProductById($id)
    {

    }

    public function getImagesByProduct($id)
    {
        $product = $this->product->with('images')->find($id);
        return $product->images;
    }

    public function createProduct($data)
    {
        $product = $this->product->create([
            'name' => $data['name'],
            'upc' => $data['upc'],
            'part_number' => $data['part_number'],
            'brand_id' => $data['brand_id'],
        ]);
        $product->categories()->sync($data['categories']);
        foreach ($data['images'] as $image) {
            $product->images()->create([
                'name' => $image['name'],
                'path' => $image['path'],
            ]);
        }
        $product->saveorfail();
        return $product;

    }

    public function updateProduct($data, $id)
    {
        $product = $this->product->find($id);
        $product->update([
            'name' => $data['name'],
            'upc' => $data['upc'],
            'part_number' => $data['part_number'],
            'brand_id' => $data['brand_id'],
        ]);
        $product->categories()->sync($data['categories']);
        foreach ($data['images'] as $image) {
            $product->images()->create([
                'name' => $image['name'],
                'path' => $image['path'],
            ]);
        }
        $product->saveorfail();
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = $this->product->find($id);
        $product->categories()->detach();
        $product->images()->delete();
        $product->delete();
    }
}

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
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function getAllProducts()
    {
        $products = $this->product::select('product.*')
            ->join('brand', 'product.brand_id', '=', 'brand.id')
            ->join('image', 'product.id', '=', 'image.product_id')
            ->join('category_product', 'product.id', '=', 'category_product.product_id')
            ->join('category', 'category_product.category_id', '=', 'category.id')
            ->distinct()
            ->get();
        // $products = $this->product::with('brand')->with('images')->with('categories')->get();
        return $products;
    }

    public function getProductById($id)
    {
        $product = $this->product::select('product.*')
            ->where('product.id', '=', $id)
            ->join('brand', 'product.brand_id', '=', 'brand.id')
            ->join('image', 'product.id', '=', 'image.product_id')
            ->join('category_product', 'product.id', '=', 'category_product.product_id')
            ->join('category', 'category_product.category_id', '=', 'category.id')
            ->first();
        // $product = $this->product->with('brand')->with('images')->with('categories')->find($id);
        return $product;

    }

    public function getAllBrands()
    {
        $brand = $this->brand->get();
        return $brand;
    }

    public function getAllCategories()
    {
        $category = $this->category->get();
        return $category;
    }

    public function getImagesByProduct($id)
    {
        $product = $this->product->with('images')->find($id);
        return $product->images;
    }

    public function getImagesByArrayId($ids)
    {
        $images = $this->image->whereIn('id', $ids)->get();
        return $images;
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
        foreach ($data['deletedImages'] as $image) {
            $product->images()->where('id', $image)->delete();
        }
        $product->saveorfail();
        return $product;
    }

    public function deleteProduct($id)
    {
        try {
            $product = $this->product->findOrFail($id);
            $product->delete();
        } catch (ModelNotFoundException $e) {
            throw new \Exception('El producto no existe');
        }
    }

    public function restoreProduct($id)
    {
        try {
            $product = $this->product->withTrashed()->findOrFail($id);
            $product->restore();
        } catch (ModelNotFoundException $e) {
            throw new \Exception('El producto no existe');
        }
    }
        
    
}

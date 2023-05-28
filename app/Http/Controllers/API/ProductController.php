<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Interfaces\ProductInterface;
use App\Traits\ImageUploaderTrait;
use App\Models\Product;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ImageUploaderTrait;
    private $productInterface;

    public function __construct(ProductInterface $productInterface)
    {
        //inicializo la variable productInterface
        $this->productInterface = $productInterface;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        try {
            $products = $this->productInterface->getAllProducts();
            return new Response([
                'products' => new ProductCollection($products)
            ], HttpResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        } catch (\Exception $th) {
            return new Response([
                'error' => 'Error al obtener los productos',
                'message' => $th->getMessage()
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
                $image_array = $this->uploadImages($request->images, 'product_images/');
                $request->merge(['images' => $image_array]);
                $product = $this->productInterface->createProduct($request->all());
            DB::commit();
            return new Response([
                'message' => 'Producto creado correctamente',
                'product' => $product
            ], HttpResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        } catch (\Exception $th) {
            DB::rollBack();
            return new Response([
                'error' => 'Error al crear el producto',
                'message' => $th->getMessage()
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $imagesToAdd = [];
            DB::beginTransaction();
                $existing_images = $this->productInterface->getImagesByProduct($id);
                foreach($request->images as $new_image){
                    if(!$existing_images->contains('name', $new_image['name'])){
                        array_push($imagesToAdd, $new_image);
                    }
                }     
                $image_array = $this->uploadImages($imagesToAdd, 'product_images/');
                $request->merge(['images' => $image_array]);
                $product = $this->productInterface->updateProduct($request->all(), $id);
            DB::commit();
            return new Response([
                'message' => 'Producto actualizado correctamente',
                // 'product' => $product
            ], HttpResponse::HTTP_OK, ['Content-Type' => 'application/json']);
            
        } catch (\Exception $th) {
            DB::rollBack();
            return new Response([
                'error' => 'Error al actualizar el producto',
                'message' => $th->getMessage()
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = $this->productInterface->deleteProduct($id);
            return new Response([
                'message' => 'Producto eliminado correctamente',
                'product' => $product
            ], HttpResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        } catch (\Exception $th) {
            return new Response([
                'error' => 'Error al eliminar el producto',
                'message' => $th->getMessage()
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }
}

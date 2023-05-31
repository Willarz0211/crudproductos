<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

trait ImageUploaderTrait
{
    //trait para carga de imagenes en base64 con manejo de arrays
    //se solicitó el envio de la imagen como array con los campos name y file
    public function uploadImages($imageArray, $path)
    {
        //array para imagenes que se enviarán al modelo
        $arrayImages = [];
        foreach ($imageArray as $image) {
            $Base64Img = $image['file'];
            list(, $Base64Img) = explode(';', $Base64Img);
            list(, $Base64Img) = explode(',', $Base64Img);
            $Base64Img = base64_decode($Base64Img);
            $type = pathinfo($image['name'], PATHINFO_EXTENSION);
            //se genera un nombre unico para la imagen
            $image_name = uniqid() . ".".$type;
            //se genera la ruta en donde estara la imagen en base de datos
            $pathToSaved = $path . $image_name;
            Storage::put($path.'\\'.$image_name, $Base64Img);
            //se guarda la info en el array que se envia al modelo
            array_push($arrayImages, [
                'name' => $image_name,
                'path' => $pathToSaved
            ]);
        }
        return $arrayImages;
    }

    public function deleteImages($imageArray)
    {
        foreach ($imageArray as $image) {
            $image_path = str_replace('\\', '/', storage_path('app' . '\\' . $image->path));
            File::exists($image_path) ? File::delete($image_path) : '';
        }
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                
                    "name" => "required|string|max:250",
                    "upc" => "required|string|max:250",
                    "part_number" => "required|string|max:250",
                    "brand_id" => "required|integer",
                    "categories" => "required|array",
                    "categories.*" => "integer",
                    "images" => "sometimes|array",
                    "images.*.file" => "sometimes|base64image",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "message" => "Los datos proporcionados son incorrectos",
            "errores" => $validator->errors()
        ], HttpResponse::HTTP_BAD_REQUEST));
    }
}

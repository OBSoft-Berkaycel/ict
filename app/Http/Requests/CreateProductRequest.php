<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Validation\Rule;
use App\Rules\UniqueProductName;

class CreateProductRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 
                'string', 
                'max:255',
                new UniqueProductName()
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'stock_status' => ['required', 'boolean'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'message' => 'Please check the fields!',
            'errors' => $validator->errors()->toArray()
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    
        throw new HttpResponseException($response);
    }
}

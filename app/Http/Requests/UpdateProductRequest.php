<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Rules\UniqueProductNameForUpdate;

class UpdateProductRequest extends FormRequest
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
                new UniqueProductNameForUpdate($this->route('productId'))
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'stock_status' => ['required', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $product = \App\Models\Products::withTrashed()->find($this->route('productId'));

            if ($product && $product->trashed()) {
                $validator->errors()->add('warning', 'Bu ürün silinmiş ancak yine de güncellenebilir.');
            }
        });
    }

    public function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'message' => 'Lütfen alanları kontrol ediniz!',
            'errors' => $validator->errors()->toArray()
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);

        if ($validator->errors()->has('warning')) {
            $response->original['message'] = $validator->errors()->get('warning');
        }
    
        throw new HttpResponseException($response);
    }
}

<?php

namespace App\Http\Requests\Product;

use App\Enums\Product\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'productId' => 'required',
            'nameEn' => ['required', Rule::unique('product_translations', 'name')
            ->ignore($this->productId, 'product_id')->where('locale', 'en')],
            'nameAr' => ['required', Rule::unique('product_translations', 'name')
            ->ignore($this->productId, 'product_id')->where('locale', 'ar')],
            'descriptionEn' => ['nullable'],
            'descriptionAr' => ['nullable'],
            'slugEn' => ['required'],
            'slugAr' => ['required'],
            'contentEn' => ['required'],
            'contentAr' => ['required'],
            'metaDataEn' => ['required'],
            'metaDataAr' => ['required'],
            'isActive' => ['required', new Enum(ProductStatus::class)],
            'images' => ['nullable'],
            'productCategoryId' => ['nullable']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}

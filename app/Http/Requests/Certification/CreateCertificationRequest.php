<?php

namespace App\Http\Requests\Certification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class CreateCertificationRequest extends FormRequest
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
            'titleEn' => ['required', 'unique:certification_translations,title,NULL,id,locale,en'],
            'titleAr' => ['required', 'unique:certification_translations,title,NULL,id,locale,ar'],
            'descriptionEn' => ['required'],
            'descriptionAr' => ['required'],
            'isPublished' => ['required'],
            'image' => ['nullable'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }
}

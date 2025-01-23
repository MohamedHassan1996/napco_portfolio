<?php

namespace App\Http\Requests\Certification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UpdateCertificationRequest extends FormRequest
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
            'certificationId' => 'required',
            'titleEn' => ['required', Rule::unique('certification_translations', 'title')
            ->ignore($this->certificationId, 'certification_id')
            ->where('locale', 'en')],
            'titleAr' => ['required', Rule::unique('certification_translations', 'title')
            ->ignore($this->certificationId, 'certification_id')
            ->where('locale', 'ar')],
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

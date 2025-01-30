<?php

namespace App\Http\Requests\FrontPage;

use App\Enums\FrontPage\FrontPageStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateFrontPageRequest extends FormRequest
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
            'frontPageId' => 'required',
            'titleEn' => ['required', Rule::unique('front_page_translations', 'title')
            ->ignore($this->frontPageId, 'front_page_id')->where('locale', 'en')],
            'titleAr' => ['required', Rule::unique('front_page_translations', 'title')
            ->ignore($this->frontPageId, 'front_page_id')->where('locale', 'ar')],
            'slugEn' => ['nullable'],
            'slugAr' => ['nullable'],
            'metaDataEn' => ['nullable'],
            'metaDataAr' => ['nullable'],
            'isActive' => ['required', new Enum(FrontPageStatus::class)],
            'controllerName' => ['required', Rule::unique('front_pages', 'controller_name')
            ->ignore($this->frontPageId, 'front_page_id')]
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}

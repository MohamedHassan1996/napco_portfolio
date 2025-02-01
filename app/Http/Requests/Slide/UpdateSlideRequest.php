<?php

namespace App\Http\Requests\Slide;

use Illuminate\Validation\Rules\Enum;
use App\Enums\Slider\SliderItemStatus;
use App\Enums\Slider\SliderItemMediaType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|string',
            'media' => 'required|file|max:2048',
            'isActive'=>['required'|new Enum(SliderItemStatus::class)],
            'MediaType'=>['required'|new Enum(SliderItemMediaType::class)],
            'contentEn' => ['required'],
            'contentAr' => ['required'],
        ];
    }
}

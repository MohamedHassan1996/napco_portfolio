<?php

namespace App\Http\Requests\Slide;

use App\Enums\Slider\SliderItemMediaType;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Slider\SliderItemStatus;
use Illuminate\Foundation\Http\FormRequest;


class CreateSlideRequest extends FormRequest
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
            // 'title , isActive' ,MediaType ,contentEn ,contentAr
           'title'=>'required|string',
           'media' => 'required|file|max:2048',
           'isActive'=>['required'|new Enum(SliderItemStatus::class)],
           'MediaType'=>['required'|new Enum(SliderItemMediaType::class)],
            'contentEn' => ['required'],
            'contentAr' => ['required'],
        ];
    }
}

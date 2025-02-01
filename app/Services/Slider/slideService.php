<?php

namespace App\Services\Slider;

use App\Models\Slider\Slider;
use App\Models\Slider\SliderItem;
use Spatie\QueryBuilder\QueryBuilder;
use App\Enums\Slider\SliderItemStatus;
use App\Services\Upload\UploadService;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use App\Enums\Slider\SliderItemMediaType;

class slideService{
   private $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function all()
    {
        $sliderItems = QueryBuilder::for(Slider::class)
        ->with('sliderItems')
        ->get();
        return $sliderItems;

    }

    public function create(array $data)
    {

            $slider = Slider::create([
                'title' => $data['title']
            ]);
            foreach ($data['sliderItems'] as $item) {
                    if (isset($item['media']))
                    {
                        $mediaPath = $this->uploadService->uploadFile($item['media'], 'sliders');
                    }
                    //title, is_active(boolean), slider_id(sliders), media(string), media_type(string), content(json)
                    $sliderItem = new SliderItem();
                    $sliderItem->is_active =SliderItemStatus::from($item['isActive'])->value ;
                    $sliderItem->media_type =SliderItemMediaType::from($item['MediaType'])->value;
                    $sliderItem->media = $mediaPath?? null;
                    if (!empty($item['contentAr'])) {
                        $sliderItem->translateOrNew('ar')->content = $item['contentAr'];
                    }

                    if (!empty($item['contentEn'])) {
                        $sliderItem->translateOrNew('en')->content = $item['contentEn'];
                    }

                    $sliderItem->slider_id = $slider->id;
                    $sliderItem->save();
                }
            return $sliderItem;
    }

    public function edit(int $id)
    {
        $slide =Slider::with('sliderItems')->findOrFail($id);
        return $slide;
    }

    public function update(array $data)
    {

            $slider=Slider::findOrFail($data['slideId']);
            $sliderItems =$slider->sliderItems;
            $slider->update([
                'title' => $data['title']
            ]);
            foreach ($sliderItems as $item) {
                    if (isset($item['media']))
                    {
                        if($item->media)
                        {
                            Storage::disk('public')->delete($item->media);
                        }
                        $mediaPath = $this->uploadService->uploadFile($item['media'], 'sliders');
                    }
                    //title, is_active(boolean), slider_id(sliders), media(string), media_type(string), content(json)
                    $item->is_active =SliderItemStatus::from($item['isActive'])->value ;
                    $item->media_type =SliderItemMediaType::from($item['MediaType'])->value;
                    $item->media = $mediaPath?? null;
                    if (!empty($item['contentAr'])) {
                        $item->translateOrNew('ar')->content = $item['contentAr'];
                    }

                    if (!empty($item['contentEn'])) {
                        $item->translateOrNew('en')->content = $item['contentEn'];
                    }

                    $item->slider_id = $slider->id;
                    $item->save();
                }
            return $item;

    }

    public function delete(int $id)
    {
        $slide =Slider::findOrFail($id)->delete();
        return $slide;
    }


}


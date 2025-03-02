<?php

namespace App\Services\Slider;

use App\Models\FrontPage\FrontPageSection;
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
        //selectId
        $slider = Slider::create([
            'title' => $data['title']
        ]);
        if($data['frontPageSectionId'])
        {
            foreach ($data['frontPageSectionId'] as $frontPageSectionId) {
                $frontPageSectionId= FrontPageSection::findOrFail($frontPageSectionId);
                $frontPageSectionId->slider_id=$slider->id;
                $frontPageSectionId->save();
            }
        }
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
           //selectId
           $slider=Slider::findOrFail($data['slideId']);
           if (!empty($data['frontPageSectionId'])) {
            // Fetch existing IDs linked to this slider
            $existingIds = FrontPageSection::where('slider_id', $slider->id)->pluck('id')->toArray();

            // Find IDs that need to be removed (existing but not in the new array)
            $idsToDelete = array_diff($existingIds, $data['frontPageSectionId']);

            // Delete the removed IDs
            FrontPageSection::whereIn('id', $idsToDelete)->delete();

            // Update or attach new IDs
            foreach ($data['frontPageSectionId'] as $frontPageSectionId) {
                $frontPageSection = FrontPageSection::findOrFail($frontPageSectionId);
                $frontPageSection->slider_id = $slider->id;
                $frontPageSection->save();
            }
        }
            // $sliderItems =$slider->sliderItems;
            $slider->update([
                'title' => $data['title']
            ]);
            foreach ($data['sliderItems'] as $item) {
                $sliderItem = SliderItem::findOrFail($item['sliderItemId']);
                    if (isset($item['media']) )
                    {
                        if($sliderItem->media)
                        {
                            Storage::disk('public')->delete($sliderItem->media);
                        }
                        $mediaPath = $this->uploadService->uploadFile($item['media'], 'sliders');
                        $sliderItem->media = $mediaPath;
                    }
                    //title, is_active(boolean), slider_id(sliders), media(string), media_type(string), content(json)
                    $sliderItem->is_active =SliderItemStatus::from($item['isActive'])->value ;
                    $sliderItem->media_type =SliderItemMediaType::from($item['MediaType'])->value;
                    if (!empty($item['contentAr'])) {
                        $sliderItem->translateOrNew('ar')->content = $item['contentAr'];
                    }

                    if (!empty($item['contentEn'])) {
                        $sliderItem->translateOrNew('en')->content = $item['contentEn'];
                    }

                   $sliderItem->slider_id = $slider->id;
                   $sliderItem->save();
                }
            return "done";

    }

    public function delete(int $id)
    {
        $slide =Slider::findOrFail($id)->delete();
        return $slide;
    }


}


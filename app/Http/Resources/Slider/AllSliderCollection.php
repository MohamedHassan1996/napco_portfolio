<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Request;
use App\Http\Resources\Slider\AllSliderResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AllSliderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    private $pagination;

    public function __construct($resource)
    {
        $this->pagination =[
            'total' => $resource->total(),
            'count' => $resource->count(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage()
        ];
        $resource = $resource->getCollection();
        parent::__construct($resource);
    }


     public function toArray(Request $request): array
    {
        return [
            "result" => [
                'sliders' => AllSliderResource::collection(($this->collection)->values()->all()),
            ],
            'pagination' => $this->pagination
        ];
    }
}

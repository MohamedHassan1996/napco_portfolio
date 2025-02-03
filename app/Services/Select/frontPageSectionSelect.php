<?php

namespace App\Services\Select;

use App\Models\FrontPage\FrontPageSection;

class frontPageSectionSelect
{
    public function getAllFrontPageSectinos()
    {
        dd("dd");
        return FrontPageSection::all(['id as value', 'name as label']);
    }

}

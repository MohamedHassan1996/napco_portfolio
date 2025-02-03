<?php

namespace App\Http\Controllers\Api\Private\FrontPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FrontPage\PageSection;

class PageSectionController extends Controller
{
    public function store(Request $request)
    {
        $data= $request->validate([
            "frontPageId"=>"required|exists:front_pages,id",
            "frontPageSectionId"=>"required|exists:front_page_sections,id",
        ]);
        $pageSection=PageSection::create([
            "front_page_id"=>$data['frontPageId'],
            "front_page_section_id"=>$data['frontPageSectionId'],
        ]);
        return response()->json([
            'message' => __('messages.success.created')
        ], 200);
    }

}

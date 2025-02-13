<?php

namespace App\Http\Controllers\Api\Website\Blogs;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Services\Blog\BlogService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\AllBlogCollection;

class BlogUnAuthController extends Controller
{
    protected $blogService;
    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }
    public function index(Request $request)
    {
        $blogs = $this->blogService->allBlogs();
        return response()->json(
            new AllBlogCollection(PaginateCollection::paginate($blogs, $request->pageSize?$request->pageSize:10))
        , 200);

    }
}

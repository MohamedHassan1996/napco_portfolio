<?php

namespace App\Http\Controllers\Api\Website\Products;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use App\Http\Resources\Product\AllProductCollection;

class ProductUnAuthController extends Controller
{
     protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->allProducts();

        return response()->json(
            new AllProductCollection(PaginateCollection::paginate($products, $request->pageSize?$request->pageSize:10))
        , 200);

    }
}

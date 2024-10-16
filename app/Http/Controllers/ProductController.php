<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductListResource;
use App\Libraries\Repository\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function __construct(private readonly ProductRepositoryInterface $productRepository){}

    public function get(int $productId)
    {
        try {
            $product = $this->productRepository->getProductById($productId);
            if(!$product)
            {
                throw new Exception('No product found for the incoming product id!');
            }
            $productData = new ProductListResource($product);

            return response()->json([
                'status' => true,
                'product' => $productData->resolve()
            ],200);
        } catch (\Throwable $th) {
            Log::error("There is an error occured on product listing process. Error: ".$th->getMessage());
            return response()->json(['status' => false, 'message' => 'There is an error occured on product listing process.'],422);
        }
    }

    public function store(CreateProductRequest $request)
    {
        try {
            $this->productRepository->createProduct($request);

            return response()->json([
                'status' => true,
                'message' => "Product record was successfully created!"
            ],200);
        } catch (\Throwable $th) {
            Log::error("There is an error occured on product create process. Error: ".$th->getMessage());
            return response()->json(['status' => false, 'message' => 'There is an error occured on product create process.'],422);
        }
    }


    public function update(UpdateProductRequest $request, int $productId)
    {
        try {
            $this->productRepository->updateProduct($request,$productId);
            return response()->json([
                'status' => true,
                'message' => "Product record was successfully updated!"
            ],200);
        } catch (\Throwable $th) {
            Log::error("There is an error occured on product update process. Error: ".$th->getMessage());
            return response()->json(['status' => false, 'message' => 'There is an error occured on product update process.'],422);
        }
    }


    public function destroy(int $productId)
    {
        try {
            $this->productRepository->deleteProduct($productId);
            return response()->json([
                'status' => true,
                'message' => "Product record was successfully deleted!"
            ],200);
        } catch (\Throwable $th) {
            Log::error("There is an error occured on product delete process. Error: ".$th->getMessage());
            return response()->json(['status' => false, 'message' => $th->getMessage()],422);
        }
    }
}

<?php
namespace App\Libraries\Repository;

use App\Libraries\Repository\Interfaces\ProductRepositoryInterface;
use App\Models\Products;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProductById(int $productId): Products|null
    {
        return Products::find($productId);
    }

    public function createProduct(CreateProductRequest $request): void
    {
        DB::transaction(function() use($request){
            Products::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'price' => $request->get('price'),
                'stock_status' => $request->get('stock_status')
            ]);
        });
    }

    public function updateProduct(UpdateProductRequest $request, int $productId): void
    {
        DB::transaction(function() use ($request,$productId) {
            $product = Products::find($productId);
            $product->name = $request->get('name');
            $product->description = $request->get('description');
            $product->stock_status = $request->get('stock_status');
            $product->save();
        });
    }

    public function deleteProduct(int $productId): void
    {
        DB::transaction(function() use ($productId) {
            $product = Products::findOrFail($productId);

            $product->stock_status = false;
            $product->deleted_at = Carbon::now();
            $product->save();
        });
    }
}

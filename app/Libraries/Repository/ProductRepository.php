<?php
namespace App\Libraries\Repository;

use App\Libraries\Repository\Interfaces\ProductRepositoryInterface;
use App\Models\Products;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\OrderStatuses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

    public function getProductCountByOrderStatus(): Collection|null
    {
        return OrderStatuses::select('order_statuses.id as order_status_id', 'order_statuses.status as order_status_name')
            ->leftJoin('orders', 'order_statuses.id', '=', 'orders.status_id')
            ->leftJoin('order_products', 'orders.id', '=', 'order_products.order_id')
            ->selectRaw('COUNT(order_products.product_id) as product_count')
            ->groupBy('order_statuses.id', 'order_statuses.status')
            ->get();
    }

    public function getTopUsedOutOfStockProducts(): Collection|null
    {
        $oneYearAgo = Carbon::now()->subYear();
        $oneMonthAgo = Carbon::now()->subMonth();

        return Products::where('stock_status', 0)
            ->whereHas('orders', function ($query) use ($oneYearAgo) {
                $query->where('order_date', '>=', $oneYearAgo);
            })
            ->whereHas('orders', function ($query) use ($oneMonthAgo) {
                $query->where('order_date', '>=', $oneMonthAgo);
            })
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();
    }
}

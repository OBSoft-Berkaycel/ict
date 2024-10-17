<?php
namespace App\Libraries\Repository\Interfaces;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Products;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getProductById(int $productId):Products|null;
    public function createProduct(CreateProductRequest $request): void;
    public function updateProduct(UpdateProductRequest $request, int $productId): void;
    public function deleteProduct(int $productId): void;
    public function getProductCountByOrderStatus(): Collection|null;
    public function getTopUsedOutOfStockProducts(): Collection|null;
}
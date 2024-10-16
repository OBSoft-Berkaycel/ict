<?php
namespace App\Libraries\Repository\Interfaces;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Products;

interface ProductRepositoryInterface
{
    public function getProductById(int $productId):Products|null;
    public function createProduct(CreateProductRequest $request): void;
    public function updateProduct(UpdateProductRequest $request, int $productId): void;
    public function deleteProduct(int $productId): void;
}
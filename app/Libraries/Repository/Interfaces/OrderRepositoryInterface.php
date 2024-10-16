<?php
namespace App\Libraries\Repository\Interfaces;

use App\Models\Orders;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function getOrders(int $customer_id): Collection|null;
    public function getOrderByOrderNo(int $customer_id, string $order_no): Orders|Collection|null;
}
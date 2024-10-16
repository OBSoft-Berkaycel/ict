<?php
namespace App\Libraries\Repository;

use App\Libraries\Repository\Interfaces\OrderRepositoryInterface;
use App\Models\Orders;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    
    public function getOrders(int $customer_id): Collection|null
    {
        return Orders::with([
                'customer',
                'status',
            ])
            ->where('customer_id', $customer_id)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getOrderByOrderNo(int $customer_id, string $order_no): Orders|Collection|null
    {
        return Orders::with([
                'customer',
                'status',
            ])
            ->where('customer_id', $customer_id)
            ->where('orders.order_no',$order_no)
            ->orderBy('id', 'DESC')
            ->first();
    }
}

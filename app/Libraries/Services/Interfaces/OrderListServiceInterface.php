<?php
namespace App\Libraries\Services\Interfaces;

use App\Http\Requests\OrderListRequest;

interface OrderListServiceInterface
{
    public function listAllOrders(OrderListRequest $request);
    public function listOrderByOrderNo(OrderListRequest $request);
}
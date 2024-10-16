<?php
namespace App\Libraries\Services;

use App\Http\Requests\OrderListRequest;
use App\Libraries\Services\Interfaces\OrderListServiceInterface;
use App\Http\Resources\OrderListResource;
use App\Libraries\Repository\Interfaces\OrderRepositoryInterface;

class OrderListService implements OrderListServiceInterface
{

    public function __construct(private readonly OrderRepositoryInterface $orderRepository){}

    public function listAllOrders(OrderListRequest $request)
    {
        return new OrderListResource($this->orderRepository->getOrders($request->get('customer_id')));
    }

    public function listOrderByOrderNo(OrderListRequest $request)
    {
        return new OrderListResource($this->orderRepository->getOrderByOrderNo($request->get('customer_id'),$request->get('order_no')));
    }
}

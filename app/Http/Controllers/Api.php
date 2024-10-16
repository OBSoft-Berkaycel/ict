<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderListRequest;
use App\Libraries\Repository\Interfaces\OrderRepositoryInterface;
use App\Libraries\Services\Interfaces\OrderListServiceInterface;

class Api
{
    public function __construct(private readonly OrderRepositoryInterface $orderRepository, private readonly OrderListServiceInterface $orderListService){}

    public function orders(OrderListRequest $request)
    {
        try {
            if(!$request->has('order_no') || !$request->get('order_no'))
            {
                $data = $this->orderListService->listAllOrders($request);
            }
            else
            {
                $data = $this->orderListService->listOrderByOrderNo($request);
            }

            return response()->json([
                'orders' => $data->resolve(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

}

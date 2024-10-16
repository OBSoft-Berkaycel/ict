<?php

namespace App\Http\Resources;

use App\Models\Orders;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // FIXME
        /* İstenilen dönüş değeri;
            Siparişe ait bilgiler, durum bilgisi ile birlikte order altında,
            Müşteri isim/soyisim customer altında
            Siparişteki ürünlerin isimleri ve ID'leri products altında. Bir ürün siparişten sonra ürün tablosunda pasife alınmış olsa dahi bu endpointte listelenmelidir
         */
        
        $orders = $this->resource;

        if($orders instanceof Collection)
        {
            $response = [];
            foreach ($orders as $order) {
                $response[] = [
                    'order' => [
                        "order_id" => $order->id,
                        "order_no" => $order->order_no,
                        "order_date" => $order->order_date,
                        "status_id" => $order->status_id,
                        "shipment_address" => $order->shipment_address
                    ],
                    'customer' => $order->customer->toArray(),
                    'products' => $order->orderProducts->toArray()
                ];
            }
            return $response;
        }

        if ($orders instanceof Orders) {
            return array(
                'order' => [
                    "order_id" => $orders->id,
                    "order_no" => $orders->order_no,
                    "order_date" => $orders->order_date,
                    "status_id" => $orders->status_id,
                    "shipment_address" => $orders->shipment_address
                ],
                'customer' => $orders->customer->toArray(),
                'products' => $orders->orderProducts->toArray()
            );
        }

        

        return [
            'order' => [],
            'customer' => [],
            'products' => []
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\AffiliateService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller {

    protected $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse {
        /*
         * @param  array{order_id: string, subtotal_price: float, merchant_domain: 
         * string, discount_code: string, customer_email: string, customer_name: string} $data
         */
        $order = $this->orderService->processOrder([
            "order_id" => random_int(1000, 9999),
            "subtotal_price" => random_int(1000, 9999),
            "discount_code" => "ABD123",
            "customer_email" => "sheikhabdulrehman8@gmail.com",
            "customer_name" => "Abdulrehman"
        ]);

        return response()->json([
                    "status" => "success",
                    "message" => "Order created successfully.",
                    "data" => $order,
                        ], JsonResponse::HTTP_OK);
    }

}

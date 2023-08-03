<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Services\MerchantService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MerchantController extends Controller {

    protected $merchantService;
    protected $orderService;

    public function __construct(MerchantService $merchantService, OrderService $orderService) {
        $this->merchantService = $merchantService;
        $this->orderService = $orderService;
    }

    public function store(Request $request) {
        //We can use custom request validator as well
        //Due to quick achieve, I am adding default validation rules. 
        $request->validate([
            "domain" => "required|string",
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "api_key" => "required",
        ]);

        return Response::json([
                    "status" => "success",
                    "code" => "200",
                    "message" => "Merchant created successfully",
                    "data" => $this->merchantService->register($request->all())
        ]);
    }

    /**
     * Useful order statistics for the merchant API.
     * 
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse {
        return Response::json([
                    "status" => "success",
                    "code" => "200",
                    "message" => "Orders list fetched successfully.",
                    "data" => $this->orderService->orderStats($request->all())
        ]);
    }

}

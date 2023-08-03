<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Repositories;

use App\Models\Order;

/**
 * Description of OrdersRepository
 *
 * @author Abdul
 */
class OrdersRepository extends BaseRepository {

    protected $model;

    public function __construct(Order $model) {
        $this->model = $model;
    }

    public function getUnpaidOrders(): Collect {
        return $this->model
                        ->where("payout_status", Order::STATUS_UNPAID)
                        ->whereNotNull("affiliate_id")
                        ->get();
    }

    /**
     * Useful order statistics for the merchant API.
     * 
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, 
     * commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(array $data): Collect {
        return $this->model
                        ->selectRaw("COUNT(id) as count, SUM(subtotal) as revenue, SUM(commission_owed) as commission_owed")
                        ->whereDate("created_at", ">=", $data['from_date'])
                        ->whereDate("created_at", "<=", $data['to_date'])
                        ->groupBy("merchant_id")
                        ->get();
    }

}

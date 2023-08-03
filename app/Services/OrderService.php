<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Repositories\OrdersRepository;
use App\Repositories\AffiliateRepository;
use App\Repositories\UsersRepository;
use App\Models\Order;
use App\Models\User;

class OrderService {

    protected $orderRepository;
    protected $affiliateRepository;
    protected $userRepository;

    public function __construct(OrdersRepository $orderRepository, UsersRepository $userRepository, AffiliateRepository $affiliateRepository) {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->affiliateRepository = $affiliateRepository;
    }

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: 
     * string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data) {
        $user = $this->userRepository->getOne("email", $data['customer_email']);
        $order = $this->orderRepository->getOne("order_id", $data['order_id']);
        if ($order) {
            //Cond: This method should also ignore duplicates based on order_id.
            return;
        }

        if (!$user->affiliate) {
            //Cond: This should create a new affiliate if the customer_email is not already associated with one.
            $user->affiliate = $this->affiliateRepository->store([
                "user_id" => $user->merchant->user_id,
                "merchant_id" => $user->merchant->id,
                "name" => $data["customer_name"],
                "email" => $data["customer_email"],
                "commission_rate" => $user->merchant->default_commission_rate
            ]);
        }

        //Cond: Process an order and log any commissions.
        $order = $this->orderRepository->store([
            "merchant_id" => $user->merchant->id ?? NULL,
            "affiliate_id" => $user->affiliate->id ?? NULL,
            "subtotal" => $data['subtotal_price'],
            "commission_owed" => $user->merchant->default_commission_rate,
            "discount_code" => $data['discount_code'],
            "order_id" => $data['order_id'],
            "merchant_domain" => $user->merchant->domain,
            "customer_email" => $data['customer_email'],
            "customer_name" => $data['customer_name']
        ]);
    }

    /**
     * Useful order statistics for the merchant API.
     * 
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, 
     * commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(array $data) {
        return $this->orderRepository->orderStats($data);
    }

}

<?php

namespace App\Services;

use App\Jobs\PayoutOrderJob;
use Illuminate\Support\Facades\Hash;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use App\Repositories\UsersRepository;
use App\Repositories\MerchantRepository;
use App\Repositories\OrdersRepository;

class MerchantService {

    protected $usersRepository;
    protected $merchantRepository;
    protected $orderRepository;

    public function __construct(UsersRepository $usersRepository, MerchantRepository $merchantRepository, OrdersRepository $orderRepository) {
        $this->usersRepository = $usersRepository;
        $this->merchantRepository = $merchantRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Register a new user and associated merchant.
     * Hint: Use the password field to store the API key.
     * Hint: Be sure to set the correct user type according to the constants in the User model.
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return Merchant
     */
    public function register(array $data): Merchant {
        //Register a new user
        $userData = [
            "name" => $data['name'],
            "email" => $data['email'],
            "email_verified_at" => NULL,
            "password" => Hash::make($data['api_key']), //Hint: Use the password field to store the API key.
            "type" => User::TYPE_MERCHANT, //Hint: Be sure to set the correct user type according to the constants in the User model.
        ];

        $user = $this->usersRepository->store($userData);
        $merchantData = [
            "user_id" => $user->id,
            "domain" => $data['domain'],
            "display_name" => $data['name']
        ];
        return $this->merchantRepository->store($merchantData); //return Merchant
    }

    /**
     * Update the user
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return void
     */
    public function updateMerchant(User $user, array $data) {
        if ($user) {
            $user->name = $data['name'] ?? $data->name;
            $user->email = $data['email'] ?? $data->email;
            if (isset($data['api_key']) && !empty($data['api_key'])) {
                $user->password = Hash::make($data['api_key']);
            }

            $user->save();
        }
    }

    /**
     * Find a merchant by their email.
     * Hint: You'll need to look up the user first.
     *
     * @param string $email
     * @return Merchant|null
     */
    public function findMerchantByEmail(string $email): ?Merchant {
        $user = $this->usersRepository->getOne('email', $email);
        return $user->merchant; // It will be merchant or null 
    }

    /**
     * Pay out all of an affiliate's orders.
     * Hint: You'll need to dispatch the job for each unpaid order.
     *
     * @param Affiliate $affiliate
     * @return void
     */
    public function payout(Affiliate $affiliate) {
        $orders = $this->orderRepository->getUnpaidOrders();
        foreach ($orders as $order) {
            PayoutOrderJob::dispatch($order);
        }
    }

}

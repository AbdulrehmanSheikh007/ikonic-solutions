<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use App\Repositories\AffiliateRepository;
use Illuminate\Support\Facades\Mail;

class AffiliateService {

    protected $affiliateRepository;

    public function __construct(AffiliateRepository $affiliateRepository) {
        $this->affiliateRepository = $affiliateRepository;
    }

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate {
        //I don't know what is the purpose of $email and $name when we don't have it in affiliate table 
        //We already have Merchant $merchant which will automatically fetched by id via route 
        //So I am not getting merchant via using $email 
        return $this->affiliateRepository->store([
                    "user_id" => $merchant->user_id,
                    "merchant_id" => $merchant->id,
                    "name" => $name,
                    "email" => $email,
                    "commission_rate" => $commissionRate
        ]);
    }

}

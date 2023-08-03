<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Mail\Payout;
use Illuminate\Support\Str;
use RuntimeException;
use Illuminate\Support\Facades\Mail;

/**
 * You don't need to do anything here. This is just to help
 */
class ApiService {

    /**
     * Create a new discount code for an affiliate
     *
     * @param Merchant $merchant
     *
     * @return array{id: int, code: string}
     */
    public function createDiscountCode(Merchant $merchant): array {
        return [
            'id' => rand(0, 100000),
            'code' => Str::uuid()
        ];
    }

    /**
     * Send a payout to an email
     *
     * @param  string $email
     * @param  float $amount
     * @return void
     * @throws RuntimeException
     */
    public function sendPayout(string $email, float $amount) {
        try {
            Mail::to($request->user())->send(new Payout($email, $amount));
            return true;
        } catch (\Exception $e) {
            return false;
        } catch (RuntimeException $e) {
            return false;
        }
    }

}

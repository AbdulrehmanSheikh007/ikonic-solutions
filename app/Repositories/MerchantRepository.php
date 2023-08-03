<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Repositories;

use App\Models\Merchant;

/**
 * Description of MerchantRepository
 *
 * @author Abdul
 */
class MerchantRepository extends BaseRepository {

    protected $model;

    public function __construct(Merchant $merchant) {
        $this->model = $merchant;
    }

}

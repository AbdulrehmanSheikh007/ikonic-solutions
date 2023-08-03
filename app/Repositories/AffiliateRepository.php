<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Repositories;

use App\Models\Affiliate;

/**
 * Description of AffiliateRepository
 *
 * @author Abdul
 */
class AffiliateRepository extends BaseRepository {

    protected $model;

    public function __construct(Affiliate $affiliate) {
        $this->model = $affiliate;
    }

}

<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Repositories;

use App\Models\User;

/**
 * Description of UsersRepository
 *
 * @author Abdul
 */
class UsersRepository extends BaseRepository {

    protected $model;

    public function __construct(User $user) {
        $this->model = $user;
    }

}

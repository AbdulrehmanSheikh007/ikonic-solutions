<?php

namespace App\Repositories;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of BaseRepository
 *
 * @author Abdul
 */
class BaseRepository {

    protected $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function getAll($columns = [], $paginate = false) {
        $return = $this->model;
        if (!empty($columns)) {
            $return = $this->model->select($columns);
        }

        if ($paginate) {
            return $this->model->paginate(\Config::get('constants.per_page'));
        }

        $this->model->get();
    }

    public function getOne($column = 'id', $value) {
        $return = $this->model;
        return $this->model->where($column, $value)->first();
    }

    public function store($data) {
        return $this->model->create($data);
    }

    public function update($data, $column, $value) {
        return $this->model->where($column, $value)->update($data);
    }

    public function delete($id) {
        return $this->model->where("id", $id)->delete();
    }

}

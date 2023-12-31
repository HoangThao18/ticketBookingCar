<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
  //model muốn tương tác
  protected $model;

  //khởi tạo
  public function __construct()
  {
    $this->setModel();
  }

  //lấy model tương ứng
  abstract public function getModel();

  /**
   * Set model
   */
  public function setModel()
  {
    $this->model = app()->make(
      $this->getModel()
    );
  }

  public function getAll()
  {
    return $this->model->get();
  }

  public function find($id)
  {
    $result = $this->model->findOrFail($id);
    return $result;
  }

  public function create($attributes = [])
  {
    return $this->model->create($attributes);
  }

  public function update($id, $attributes = [])
  {
    $result = $this->model->findOrFail($id);
    if ($result) {
      $result->update($attributes);

      return $result;
    }
    return false;
  }

  public function delete($id)
  {
    $result = $this->model->findOrFail($id);
    if ($result) {
      $result->delete();

      return true;
    }

    return false;
  }
}

<?php

namespace App\Model\User;

use Hasdemir\Base\Crud;
use App\Model\User\Storage\UserPdo;
use App\Model\User\Storage\UserRedis;

class UserSearch extends Crud
{
  protected ?UserPdo $storage;

  public function __construct(UserPdo $storage = null)
  {
    $storage ===null ?: $this->setStorage($storage);
  }

  public function setStorage($storage)
  {
    $this->storage = $storage;
    $this->redis_storage = new UserRedis();
  }

  public function init($params)
  {
    UserCodes::currentJob(UserCodes::JOB_SEARCH);
    try {
      $rows = $this->storage;
      foreach ($params ?? [] as $key => $value) {
        $rows->where($key, $value);
      }
      $rows = $rows->get();

      $total = $this->storage->select('COUNT(*) as total')->first()->total;

      return [
        'data' => $rows,
        'total' => $total
      ];
    } finally {
      UserCodes::endJob();
    }
  }
}
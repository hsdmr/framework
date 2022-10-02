<?php

namespace App\Model\AccessToken;

use Hasdemir\Base\Crud;
use App\Model\AccessToken\Storage\AccessTokenPdo;
use App\Model\AccessToken\Storage\AccessTokenRedis;

class AccessTokenSearch extends Crud
{
  protected ?AccessTokenPdo $storage;

  public function __construct(AccessTokenPdo $storage = null)
  {
    $storage ===null ?: $this->setStorage($storage);
  }

  public function setStorage($storage)
  {
    $this->storage = $storage;
    $this->redis_storage = new AccessTokenRedis();
  }

  public function init($params)
  {
    AccessTokenCodes::currentJob(AccessTokenCodes::JOB_SEARCH);
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
      AccessTokenCodes::endJob();
    }
  }
}
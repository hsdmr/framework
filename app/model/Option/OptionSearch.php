<?php

namespace App\Model\Option;

use Hasdemir\Base\Crud;
use App\Model\Option\Storage\OptionPdo;
use App\Model\Option\Storage\OptionRedis;

class OptionSearch extends Crud
{
  protected ?OptionPdo $storage;

  public function __construct(OptionPdo $storage = null)
  {
    $storage ===null ?: $this->setStorage($storage);
  }

  public function setStorage($storage)
  {
    $this->storage = $storage;
    $this->redis_storage = new OptionRedis();
  }

  public function init($params)
  {
    OptionCodes::currentJob(OptionCodes::JOB_SEARCH);
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
      OptionCodes::endJob();
    }
  }
}
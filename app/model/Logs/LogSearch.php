<?php

namespace App\Model\Logs;

use Hasdemir\Base\Crud;
use App\Model\Logs\Storage\LogPdo;

class LogSearch extends Crud
{
  protected $storage;

  public function __construct(LogPdo $storage = null)
  {
    $storage === null ?: $this->setStorage($storage);
  }

  public function setStorage($storage)
  {
    $this->storage = $storage;
  }

  public function init($params)
  {
    LogCodes::currentJob(LogCodes::JOB_SEARCH);
    try {
      $sort = $params['sort'] ?? 'created_at';
      $start = $params['start'] ?? 0;
      $count = $params['count'] ?? 20;
      $sort_type = 'desc';
      if ($sort[0] === '-') {
        $sort = substr($sort, 1);
        $sort_type = 'asc';
      }

      $storage = $this->storage;
      
      if ($params['type']) {
        $storage->where('type', $params['type']);
      }

      if ($params['status']) {
        $storage->where('status', $params['status']);
      }
      
      $total = $storage->select('COUNT(*) as total')->first()->total;
      $storage->order($sort, $sort_type)->limit($count, $start);
      $rows = $storage->select('*')->get();

      return [
        'data' => $rows,
        'total' => $total
      ];
    } finally {
      LogCodes::endJob();
    }
  }
}

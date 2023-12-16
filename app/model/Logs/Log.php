<?php

namespace App\Model\Logs;

use Respect\Validation\Validator as v;
use Hasdemir\Base\Crud;
use Hasdemir\Helper\Json;
use App\Model\Logs\Storage\LogPdo;

class Log extends Crud
{
  protected $storage;

  public function __construct($id = null, LogPdo $storage = null)
  {
    parent::__construct();
    $storage === null ?: $this->setStorage($storage);

    if (!v::nullType()->validate($id)) {
      $this->read($id);
    }
  }

  public function setStorage($storage)
  {
    $this->storage = $storage;
  }

  public function create($params)
  {
    LogCodes::currentJob(LogCodes::JOB_CREATE);
    try {
      $this->user = $params['user'];
      $this->type = $params['type'];
      $this->ip = $params['ip'];
      $this->message = $params['message'];
      $this->status = $params['status'];
      $this->method = $params['method'];
      $this->path = $params['path'];
      $this->browser = $params['browser'];
      $this->context = $params['context'];
      $this->created_at = time();

      $this->storage->create([
        'type' => $this->type,
        'user' => $this->user,
        'ip' => $this->ip,
        'method' => $this->method,
        'path' => $this->path,
        'browser' => $this->browser,
        'message' => $this->message,
        'status' => $this->status,
        'context' => Json::encode($this->context),
        'created_at' => $this->created_at,
      ]);
      $this->id = $this->storage->id;

      return $this;
    } finally {
      LogCodes::endJob();
    }
  }

  public function read($id = null)
  {
    LogCodes::currentJob(LogCodes::JOB_READ);
    try {
      $log = $this->storage;

      if ($id) {
        $log->where('id', $id);
      }

      $log->withHidden()->first();

      if ($log) {
        foreach ($log->toArray() as $key => $value) {
          $this->{$key} = $value;
          if ($key === 'context') {
            $this->context = Json::decode($value);
          }
        }
      }

      return $this;
    } finally {
      LogCodes::endJob();
    }
  }
}

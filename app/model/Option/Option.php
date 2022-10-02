<?php

namespace App\Model\Option;

use Hasdemir\Base\Crud;
use App\Model\Option\Storage\OptionPdo;
use App\Model\Option\Storage\OptionRedis;

class Option extends Crud
{
  protected ?OptionPdo $storage;
  protected ?OptionRedis $redis_storage;

  public function __construct($id = null, OptionPdo $storage = null)
  {
    $storage ===null ?: $this->setStorage($storage);
    
    if ($id !== null) {
      $this->read($id);
    }
  }

  public function setStorage($storage)
  {
    $this->storage = $storage;
    $this->redis_storage = new OptionRedis();
  }

  public function create($params)
  {
    OptionCodes::currentJob(OptionCodes::JOB_CREATE);
    try {

    } finally {
      OptionCodes::endJob();
    }
  }

  public function read($id)
  {
    OptionCodes::currentJob(OptionCodes::JOB_READ);
    try {

    } finally {
      OptionCodes::endJob();
    }
  }

  public function update($id, $params)
  {
    OptionCodes::currentJob(OptionCodes::JOB_UPDATE);
    try {

    } finally {
      OptionCodes::endJob();
    }
  }

  public function delete($id)
  {
    OptionCodes::currentJob(OptionCodes::JOB_DELETE);
    try {

    } finally {
      OptionCodes::endJob();
    }
  }
}
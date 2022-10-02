<?php

namespace App\Model\AccessToken;

use Respect\Validation\Validator as v;
use Hasdemir\Base\Crud;
use App\Model\AccessToken\Storage\AccessTokenPdo;
use App\Model\AccessToken\Storage\AccessTokenRedis;
use Hasdemir\Helper\Json;
use UnexpectedValueException;

class AccessToken extends Crud
{
  protected $storage;
  const TYPES = ['session', 'reset-password'];

  public function __construct($token = null, AccessTokenPdo $storage = null)
  {
    $storage === null ?: $this->setStorage($storage);

    if (!v::nullType()->validate($token)) {
      $this->read($token);
    }
  }

  public function setStorage($storage)
  {
    $this->storage = $storage;
    $this->redis_storage = new AccessTokenRedis();
  }

  public function create($params)
  {
    AccessTokenCodes::currentJob(AccessTokenCodes::JOB_CREATE);
    try {
      $this->user_id = $params['user_id'];
      $this->token = $params['token'];
      $this->type = $params['type'] ?? self::TYPES[0];
      $this->scope = $params['scope'] ?? [];
      $this->expires_at = $params['expires_at'];
      $this->created_at = time();
      $this->updated_at = time();

      $this->storage->create([
        'user_id' => $this->user_id,
        'token' => $this->token,
        'type' => $this->type,
        'scope' => Json::encode($this->scope),
        'expires_at' => $this->expires_at,
        'updated_at' => $this->updated_at,
      ]);

      return $this;
    } finally {
      AccessTokenCodes::endJob();
    }
  }

  public function read($token)
  {
    AccessTokenCodes::currentJob(AccessTokenCodes::JOB_READ);
    try {
      $result = $this->storage->select('*')->where('token', $token)->first();

      if ($result) {
        foreach ($result->toArray() ?? [] as $key => $value) {
          if (v::in($this->storage->getFields())->validate($key)) {
            $this->{$key} = $value;
            if ($key === 'scope') {
              $this->{$key} = Json::decode($value);
            }
          }
        }
      }
      return $this;
    } finally {
      AccessTokenCodes::endJob();
    }
  }

  public function update($params)
  {
    AccessTokenCodes::currentJob(AccessTokenCodes::JOB_UPDATE);
    try {
      foreach ($params ?? [] as $key => $value) {
        if (v::in($this->storage->getFields())->validate($key)) {
          $this->{$key} = $value;
        }
      }
      $this->updated_at = time();

      $this->storage->update([
        'user_id' => $this->user_id,
        'token' => $this->token,
        'type' => $this->type,
        'scope' => $this->scope,
        'expires_at' => $this->expires_at,
        'updated_at' => $this->updated_at,
      ]);

      return $this;
    } finally {
      AccessTokenCodes::endJob();
    }
  }

  public function delete()
  {
    AccessTokenCodes::currentJob(AccessTokenCodes::JOB_DELETE);
    try {
      $result = $this->storage->where('token', $this->token)->first();
      $result->delete();
      return true;
    } finally {
      AccessTokenCodes::endJob();
    }
  }

  protected function checkValue($name, $value, $object = false)
  {
    switch ($name) {
      case 'types':
        if (!v::in(self::TYPES)->validate($value)) {
          throw new UnexpectedValueException("'types' must be 'temporary' or 'api'");
        }
        break;
    }
    return $value;
  }
}

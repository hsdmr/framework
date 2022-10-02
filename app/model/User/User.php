<?php

namespace App\Model\User;

use Respect\Validation\Validator as v;
use Hasdemir\Base\Crud;
use App\Model\User\Storage\UserPdo;
use App\Model\User\Storage\UserRedis;

class User extends Crud
{
  protected $storage;
  protected $redis_storage;

  public function __construct($id = null, $email = null, UserPdo $storage = null)
  {
    parent::__construct();
    $storage === null ?: $this->setStorage($storage);

    if (!v::nullType()->validate($id) || !v::nullType()->validate($email)) {
      $this->read($id, $email);
    }
  }

  public function setStorage($storage)
  {
    $this->storage = $storage;
    $this->redis_storage = new UserRedis();
  }

  public function create($params)
  {
    UserCodes::currentJob(UserCodes::JOB_CREATE);
    try {
      $this->first_name = $params['first_name'];
      $this->last_name = $params['last_name'];
      $this->email = $params['email'];
      $this->phone = $params['phone'];
      $this->email_verified_at = $params['email_verified_at'];
      $this->password = $params['password'];
      $this->deleted_at = null;
      $this->created_at = time();
      $this->updated_at = time();

      $this->storage->create([
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'email' => $this->email,
        'phone' => $this->phone,
        'email_verified_at' => $this->email_verified_at,
        'password' => $this->password,
        'deleted_at' => $this->deleted_at,
        'expires_at' => $this->expires_at,
        'updated_at' => $this->updated_at,
      ]);

      return $this;
    } finally {
      UserCodes::endJob();
    }
  }

  public function read($id = null, $email = null)
  {
    UserCodes::currentJob(UserCodes::JOB_READ);
    try {
      $user = $this->storage;

      if ($id) {
        $user->where('id', $id);
      }

      if ($email) {
        $user->where('email', $email);
      }

      $user->first();

      if ($user) {
        foreach ($user->toArray() as $key => $value) {
          $this->{$key} = $value;
        }
      }

      return $this;
    } finally {
      UserCodes::endJob();
    }
  }

  public function update($params)
  {
    UserCodes::currentJob(UserCodes::JOB_UPDATE);
    try {
      foreach ($params ?? [] as $key => $value) {
        if (v::in($this->storage->getFields())->validate($key)) {
          $this->{$key} = $value ?? '';
        }
      }
      $this->updated_at = time();

      $this->storage->update([
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'email' => $this->email,
        'phone' => $this->phone,
        'email_verified_at' => $this->email_verified_at,
        'password' => $this->password,
        'deleted_at' => $this->deleted_at,
        'updated_at' => $this->updated_at,
      ]);

      return $this;
    } finally {
      UserCodes::endJob();
    }
  }

  public function delete()
  {
    UserCodes::currentJob(UserCodes::JOB_DELETE);
    try {
      $user = $this->storage;

      if ($this->id) {
        $user->where('id', $this->id);
      }

      if ($this->email) {
        $user->where('email', $this->email);
      }

      $user->first();
      $user->delete();
      return true;
    } finally {
      UserCodes::endJob();
    }
  }
}

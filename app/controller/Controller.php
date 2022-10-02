<?php

namespace App\Controller;

use App\Model\AccessToken\Storage\AccessTokenPdo;
use App\Model\Option\Storage\OptionPdo;
use App\Model\User\Storage\UserPdo;
use Hasdemir\Base\Controller as BaseController;

class Controller extends BaseController
{
  public function __construct($request, $args)
  {
    parent::__construct($request, $args);
  }

  public function getUserStorage()
  {
    if (!isset($this->storage_instance['UserStorage'])) {
      $this->storage_instance['UserStorage'] = new UserPdo();
    }
    return $this->storage_instance['UserStorage'];
  }

  public function getOptionStorage()
  {
    if (!isset($this->storage_instance['OptionStorage'])) {
      $this->storage_instance['OptionStorage'] = new OptionPdo();
    }
    return $this->storage_instance['OptionStorage'];
  }

  public function getAccessTokenStorage()
  {
    if (!isset($this->storage_instance['AccessTokenStorage'])) {
      $this->storage_instance['AccessTokenStorage'] = new AccessTokenPdo();
    }
    return $this->storage_instance['AccessTokenStorage'];
  }
}

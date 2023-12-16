<?php

namespace App\Controller;

use App\Model\AccessToken\Storage\AccessTokenPdo;
use App\Model\Logs\Storage\LogPdo;
use App\Model\Option\Storage\OptionPdo;
use App\Model\User\Storage\UserPdo;
use Hasdemir\Base\Controller as BaseController;
use Hasdemir\Base\Log as BaseLog;
use Uzman\Logs\Log;

class Controller extends BaseController
{
  private array $storage_instance = [];
  public array $auth_user = [];

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

  public function getLogStorage()
  {
    if (!isset($this->storage_instance['LogStorage'])) {
      $this->storage_instance['LogStorage'] = new LogPdo();
    }
    return $this->storage_instance['LogStorage'];
  }

  public function __destruct()
  {
    $body = BaseLog::body();
    $context = $body['context'];
    $context['auth_user'] = $this->auth_user;
    if (count($body['error']) !== 0) {
      $context['error'] = $body['error'];
    }
    if ($context['request']['method']) {
      $log = new Log(null, $this->getLogStorage());
      $log->create([
        'type' => 'API',
        'user' => $this->auth_user['email'] ?? '',
        'ip' => $context['request']['ip'],
        'method' => $context['request']['method'],
        'path' => $context['request']['path'],
        'browser' => $context['request']['agent']['browser'],
        'status' => $body['status'],
        'message' => $body['message'],
        'context' => $context,
      ]);
    }
  }
}

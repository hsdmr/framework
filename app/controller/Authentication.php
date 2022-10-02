<?php

namespace App\Controller;

use App\Model\AccessToken\AccessToken;
use App\Model\AccessToken\AccessTokenSearch;
use App\Model\User\User;
use Hasdemir\Base\Exception\AuthenticationException;
use Hasdemir\Base\Exception\NotFoundException;
use Hasdemir\Helper\Json;

class Authentication extends Controller
{
  protected static $routes = [
    ['POST', '/login', 'login'],
    ['POST', '/register', 'register'],
    ['GET', '/check-token/{token}', 'checkToken'],
    ['POST', '/forget-password', 'forgetPassword'],
    ['PUT', '/reset-password', 'resetPassword'],
    ['DELETE', '/logout', 'logout'],
  ];

  public function __construct($request, $args)
  {
    parent::__construct($request, $args);
  }

  public function login($request, $args)
  {
    Codes::currentJob(Codes::JOB_AUTH_LOGIN);
    try {
      $post = Json::decode($request->body());

      $user = new User(null, $post['email'], $this->getUserStorage());

      if (!isset($user->id)) {
        throw new AuthenticationException("'email' is wrong");
      }

      if ($user->deleted_at != null) {
        throw new AuthenticationException("This user deleted");
      }

      if (!password_verify($post['password'], $user->password)) {
        throw new AuthenticationException("'password' is incorrect");
      }

      $access_token_search = new AccessTokenSearch($this->getAccessTokenStorage());
      $access_token_search_result = $access_token_search->init(['user_id' => $user->id, 'type' => 'temporary']);
      $token = randomString(60);

      if (count($access_token_search_result['data']) === 1) {
        $access_token = new AccessToken($access_token_search_result['data'][0]['token'], $this->getAccessTokenStorage());
        $access_token = $access_token->update([
          'user_id' => $user->id,
          'token' => sha1($token),
          'expires_at' => strtotime($_ENV['SESSION_TOKEN_LIFETIME'])
        ]);
      } else {
        $access_token = new AccessToken(null, $this->getAccessTokenStorage());
        $access_token = $access_token->create([
          'user_id' => $user->id,
          'token' => sha1($token),
          'expires_at' => strtotime($_ENV['SESSION_TOKEN_LIFETIME'])
        ]);
      }

      $return = [
        'access_token' => $token,
        'scope' => $access_token->scope,
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'phone' => $user->phone,
        'options' => $user->options,
      ];

      $this->body = $return;
      return $this->response(HTTP_CREATED);
    } finally {
      Codes::endJob();
    }
  }

  public function checkToken($request, $args)
  {
    Codes::currentJob(Codes::JOB_AUTH_CHECK);
    try {
      $token = sha1($args['token']);
      $access_token = new AccessToken($token, $this->getAccessTokenStorage());
      if (isset($access_token->token) && $access_token->expires_at > time()) {
        $this->body = [
          'type' => $access_token->type,
          'expires_at' => $access_token->expires_at,
        ];
        return $this->response(HTTP_OK);
      }
      throw new NotFoundException();
    } finally {
      Codes::endJob();
    }
  }

  public function register($request, $args)
  {
    Codes::currentJob(Codes::JOB_AUTH_REGISTER);
    try {
      $post = Json::decode($request->body());
      $user = new User(null, null, $this->getUserStorage());
      $user->create([
        'email' => $post['email'],
        'first_name' => $post['first_name'],
        'last_name' => $post['last_name'],
        'password' => sha1($post['password']),
      ]);

      $this->body = [
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'avatar' => '',
        'phone' => $user->phone,
        'email_verified_at' => $user->email_verified_at,
        'deleted_at' => $user->deleted_at,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
      ];
      return $this->response(HTTP_CREATED);
    } finally {
      Codes::endJob();
    }
  }

  public function resetPassword($request, $args)
  {
    Codes::currentJob(Codes::JOB_AUTH_RESET_PASSWORD);
    try {
      $put = Json::decode($request->body());
      $access_token = new AccessToken(sha1($put['token']), $this->getAccessTokenStorage());
      if (isset($access_token->user_id)) {
        $user = new User($access_token->user_id, null, $this->getUserStorage());
        if (isset($user->id)) {
          $user->update([
            'password' => sha1($put['password'])
          ]);
          return $this->response(HTTP_OK);
        }
      }
      throw new NotFoundException();
    } finally {
      Codes::endJob();
    }
  }

  public function forgetPassword($request, $args)
  {
    Codes::currentJob(Codes::JOB_AUTH_FORGET_PASSWORD);
    try {
      $post = Json::decode($request->body());

      $user = new User(null, $post['email'], $this->getUserStorage());

      if (!isset($user->id)) {
        throw new AuthenticationException("'email' is wrong");
      }

      if ($user->deleted_at != null) {
        throw new AuthenticationException("This user deleted");
      }

      $token = randomString(60);
      $access_token = new AccessToken(null, $this->getAccessTokenStorage());
      $access_token = $access_token->create([
        'user_id' => $user->id,
        'token' => sha1($token),
        'type' => 'reset-password',
        'expires_at' => strtotime($_ENV['RESET_PASSWORD_TOKEN_LIFETIME'])
      ]);

      $this->body = [
        'reset_password_token' => $token,
      ];
      return $this->response(HTTP_CREATED);
    } finally {
      Codes::endJob();
    }
  }

  public function logout($request, $args)
  {
    Codes::currentJob(Codes::JOB_AUTH_LOGOUT);
    try {
      $token = sha1(explode(' ', $request->headers('Authorization'))[1]);
      $access_token = new AccessToken($token, $this->getAccessTokenStorage());
      if (isset($access_token->token)) {
        if ($access_token->delete()) {
          return $this->response(HTTP_NO_CONTENT);
        }
      }
      throw new NotFoundException();
    } finally {
      Codes::endJob();
    }
  }
}

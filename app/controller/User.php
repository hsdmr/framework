<?php

namespace App\Controller;

use Hasdemir\Helper\Json;

class User extends Controller
{
  protected static $routes = [
    ['GET', '/user', 'search'],
    ['POST', '/user', 'create'],
    ['GET', '/user/{user_id}', 'read'],
    ['PUT', '/user/{user_id}', 'update'],
    ['DELETE', '/user/{user_id}', 'delete'],
  ];

  public function __construct($request, $args)
  {
    parent::__construct($request, $args);
  }

  public function search($request, $args)
  {
    Codes::currentJob('user_search');
    try {
      $get = $request->params();
    } finally {
      Codes::endJob();
    }
  }

  public function create($request, $args)
  {
    Codes::currentJob('user_create');
    try {
      $post = Json::decode($request->body());
    } finally {
      Codes::endJob();
    }
  }

  public function read($request, $args)
  {
    Codes::currentJob('user_read');
    try {
      $user_id = $args['user_id'];
    } finally {
      Codes::endJob();
    }
  }

  public function update($request, $args)
  {
    Codes::currentJob('user_update');
    try {
      $put = Json::decode($request->body());
    } finally {
      Codes::endJob();
    }
  }

  public function delete($request, $args)
  {
    Codes::currentJob('user_delete');
    try {
      $user_id = $args['user_id'];
    } finally {
      Codes::endJob();
    }
  }
}

<?php

namespace App\Controller;

use Hasdemir\Base\Controller;
use Hasdemir\Helper\Json;

class Option extends Controller
{
  protected static $routes = [
    ['GET', '/option', 'search'],
    ['POST', '/option', 'create'],
    ['GET', '/option/{option_id}', 'read'],
    ['PUT', '/option/{option_id}', 'update'],
    ['DELETE', '/option/{option_id}', 'delete'],
  ];

  public function __construct($request, $args)
  {
    parent::__construct($request, $args);
  }

  public function search($request, $args)
  {
    Codes::currentJob('option_search');
    try {
      $get = $request->params();

    } finally {
      Codes::endJob();
    }
  }
  
  public function create($request, $args)
  {
    Codes::currentJob('option_create');
    try {
      $post = Json::decode($request->body());


    } finally {
      Codes::endJob();
    }
  }
  
  public function read($request, $args)
  {
    Codes::currentJob('option_read');
    try {
      $option_id = $args['option_id'];

    } finally {
      Codes::endJob();
    }
  }
  
  public function update($request, $args)
  {
    Codes::currentJob('option_update');
    try {
      $put = Json::decode($request->body());

    } finally {
      Codes::endJob();
    }
  }
  
  public function delete($request, $args)
  {
    Codes::currentJob('option_delete');
    try {
      $option_id = $args['option_id'];

    } finally {
      Codes::endJob();
    }
  }
}
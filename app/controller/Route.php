<?php

namespace App\Controller;

use Hasdemir\Helper\Json;

class Route extends Controller
{
  protected static $routes = [
    ['GET', '/', 'home'],
    ['GET', '/{url}/{url1?}/{url2?}/{url3?}/{url4?}', 'other'],
  ];

  public function __construct($request, $args)
  {
    parent::__construct($request, $args);
  }

  public function home($request, $args)
  {
    Codes::currentJob('route_home');
    try {
      return view('index.php');
    } finally {
      Codes::endJob();
    }
  }

  public function other($request, $args)
  {
    Codes::currentJob('route_other');
    try {
      var_dump($args);
    } finally {
      Codes::endJob();
    }
  }
}

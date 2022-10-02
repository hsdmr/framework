<?php

namespace App\Model\AccessToken\Storage;

use Hasdemir\Base\PdoModel;

class AccessTokenPdo extends PdoModel
{
  protected string $table = 'access_token';
  protected array $unique = ['token'];
}

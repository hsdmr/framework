<?php

namespace App\Model\User\Storage;

use Hasdemir\Base\PdoModel;

class UserPdo extends PdoModel
{
  protected string $table = 'user';
  protected array $fields = [];
  protected array $unique = [];
  protected array $hidden = [];
  protected array $protected = [];
  protected bool $soft_delete = false;
}
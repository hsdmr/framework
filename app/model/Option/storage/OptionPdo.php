<?php

namespace App\Model\Option\Storage;

use Hasdemir\Base\PdoModel;

class OptionPdo extends PdoModel
{
  protected string $table = 'option';
  protected array $fields = [];
  protected array $unique = [];
  protected array $hidden = [];
  protected array $protected = [];
  protected bool $soft_delete = false;
}
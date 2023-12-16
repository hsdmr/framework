<?php

namespace App\Model\Logs\Storage;

use Hasdemir\Base\PdoModel;

class LogPdo extends PdoModel
{
  protected string $table = 'log';
  protected array $unique = ['id'];
}
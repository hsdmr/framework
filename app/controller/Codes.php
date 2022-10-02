<?php

namespace App\Controller;

use Hasdemir\Base\Codes as BaseCodes;

class Codes extends BaseCodes
{
  const NAMESPACE = 'controller';

  const JOB_AUTH_LOGIN = 'auth-login';
  const JOB_AUTH_REGISTER = 'auth-register';
  const JOB_AUTH_CHECK = 'auth-check';
  const JOB_AUTH_FORGET_PASSWORD = 'auth-forget-password';
  const JOB_AUTH_RESET_PASSWORD = 'auth-reset-password';
  const JOB_AUTH_LOGOUT = 'auth-logout';
}

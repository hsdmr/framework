<?php

use Hasdemir\Base\System;

class User010
{
  public function up()
  {
    $db = System::getPdo();
    $timestamps = timestamps();

    $user = "CREATE TABLE `user` (
      `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      `avatar` VARCHAR(255) NULL,
      `first_name` VARCHAR(255) NULL,
      `last_name` VARCHAR(255) NULL,
      `role` VARCHAR(50) NOT NULL,
      `email` VARCHAR(255) NOT NULL,
      `username` VARCHAR(255) NOT NULL,
      `nickname` VARCHAR(255) NULL,
      `phone` VARCHAR(255) NULL,
      `email_verified_at` BIGINT(20) NULL,
      `password` VARCHAR(255) NOT NULL,
      {$timestamps},
      PRIMARY KEY (`id`)) ENGINE = InnoDB;";

    $db->exec($user);
  }

  public function down()
  {
    $db = System::getPdo();
    $user = "DROP TABLE `user`";
    $db->exec($user);
  }
}

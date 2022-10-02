<?php

use Hasdemir\Base\System;

class AccessToken010
{
  public function up()
  {
    $db = System::getPdo();
    $timestamps = timestamps();

    $access_token = "CREATE TABLE `access_token` (
      `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` BIGINT(20) NOT NULL , 
      `token` VARCHAR(100) NOT NULL , 
      `type` VARCHAR(20) NULL , 
      `attributes` MEDIUMTEXT NULL , 
      `scope` MEDIUMTEXT NULL , 
      `expires_at` BIGINT(20) NULL , 
      {$timestamps},
      PRIMARY KEY (`id`)) ENGINE = InnoDB;";

    $db->exec($access_token);
  }

  public function down()
  {
    $db = System::getPdo();
    $access_token = "DROP TABLE `access_token`";
    $db->exec($access_token);
  }
}

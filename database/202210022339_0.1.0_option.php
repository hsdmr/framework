<?php

use Hasdemir\Base\System;

class Option010
{
  public function up()
  {
    $db = System::getPdo();
    $timestamps = timestamps();

    $option = "CREATE TABLE `option` (
      `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      `type` VARCHAR(255) NULL , 
      `type_id` BIGINT(20) NULL , 
      `key` VARCHAR(255) NOT NULL , 
      `value` TEXT NULL , 
      {$timestamps},
      PRIMARY KEY (`id`)) ENGINE = InnoDB;";

    $db->exec($option);
  }

  public function down()
  {
    $db = System::getPdo();
    $option = "DROP TABLE `option`";
    $db->exec($option);
  }
}

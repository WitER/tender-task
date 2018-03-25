<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Init extends CI_Migration {

    public function up()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `session` (
                    `id` varchar(128) NOT NULL,
                    `ip_address` varchar(60) NOT NULL,
                    `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
                    `data` blob NOT NULL,
                    KEY `ci_sessions_timestamp` (`timestamp`)
            );
        ");

        $this->db->query("
            CREATE TABLE `business` ( 
                `id` Int( 11 ) AUTO_INCREMENT NOT NULL,
                `ogrn` BigInt( 15 ) NOT NULL,
                `inn` BigInt( 12 ) NOT NULL,
                `category` Int( 1 ) NOT NULL,
                `name` VarChar( 512 ) NOT NULL,
                `short_name` VarChar( 255 ) NOT NULL,
                `region_code` Int( 3 ) NOT NULL,
                `region_type` VarChar( 30 ) NOT NULL,
                `region_name` VarChar( 255 ) NOT NULL,
                `city_type` VarChar( 30 ) NOT NULL,
                `city` VarChar( 50 ) NOT NULL,
                `street_type` VarChar( 50 ) NOT NULL,
                `street` VarChar( 50 ) NOT NULL,
                `house` VarChar( 50 ) NULL,
                `office` VarChar( 50 ) NULL,
                `new` TinyInt( 1 ) NOT NULL DEFAULT '0',
                `created` DateTime NOT NULL,
                `closed` DateTime NULL,
                `okved1` VarChar( 8 ) NOT NULL,
                `okved1_name` VarChar( 255 ) NOT NULL,
                `modified` DateTime NOT NULL,
                PRIMARY KEY ( `id` ) )
            ENGINE = InnoDB;
        ");

        $this->db->query("CREATE INDEX `idx_inn` ON `business`( `inn` );");
        $this->db->query("CREATE INDEX `idx_ogrn` ON `business`( `ogrn` );");
    }

    public function down()
    {
    }
}
<?php

    /**
     * Created by MaisonDuNet
     *
     * @author  Alexandre PERRIGAULT <alexandre@maisondunet.com>
     * @date    16/03/2022
     */

    if (!defined('_PS_VERSION_')) {
        exit;
    }

    $sql[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "mdn_image_bullet` (
        `id`      INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `id_product`    TINYINT UNSIGNED NOT NULL,
        `title`    VARCHAR(256) DEFAULT NULL,
        `image`    VARCHAR(128) DEFAULT NULL,
        `bullets`    TEXT DEFAULT NULL,
        `active`    INT(2) DEFAULT 1,
        PRIMARY KEY (`id`)
    ) ENGINE=" . _MYSQL_ENGINE_ . "  DEFAULT CHARSET=utf8";

    foreach ($sql as $query) {
        if (!Db::getInstance()->execute(trim($query))) {
            return false;
        }
    }

    return true;

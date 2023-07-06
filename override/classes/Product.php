<?php
require_once  dirname(__FILE__) . "/../../modules/mdn_imagebullet/classes/ImageBulletModel.php";

class Product extends ProductCore
{
    public function getImageBullets()
    {
        return array_map(function ($v) {
            return new ImageBulletModel($v['id']);
        }, Db::getInstance()->executeS("SELECT id FROM  `" . _DB_PREFIX_ . "mdn_image_bullet` WHERE id_product = '".$this->id."'"));
    }
}

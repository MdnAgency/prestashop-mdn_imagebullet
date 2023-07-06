<?php
class ImageBulletModel extends ObjectModel {
    public static $definition = array(
        'table' => 'mdn_image_bullet',
        'primary' => 'id',
        // 'multishop' => true,
        // 'multilang' => true,
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => array(
            'id' =>        array('type' => self::TYPE_INT,  'validate' => 'isUnsignedInt', 'required' => true, 'lang' => false),
            'id_product' =>        array('type' => self::TYPE_INT,  'validate' => 'isUnsignedInt', 'required' => true, 'lang' => false),
            'title' =>        array('type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => false, 'lang' => false),
            'image' =>        array('type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => true, 'lang' => false),
            'bullets' =>        array('type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => true, 'lang' => false),
            'active' =>        array('type' => self::TYPE_BOOL, 'required' => true, 'lang' => false),
        ),
    );

    public $id;
    public $id_product;
    public $title;
    public $image;
    public $bullets;
    public $active;



    public function __construct($id_primary = null, $id_lang = null)
    {
        $this->force_id = true;
        parent::__construct($id_primary, $id_lang);
    }

    public static function createContentTable()   {
        return true;
    }

    public function decodedBullets() {
        return json_decode($this->bullets, true);
    }
}

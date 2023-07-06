<?php

use PrestaShop\PrestaShop\Core\Product\ProductExtraContent;

require 'vendor/autoload.php';

    if (!defined('_PS_VERSION_')) {
        exit;
    }

    class Mdn_Imagebullet extends Module
    {

        public function __construct()
        {
            $this->name = 'mdn_imagebullet';
            $this->tab = 'front_office_features';
            $this->version = '1.3.0';
            $this->author = 'MaisonDuNet';
            $this->ps_versions_compliancy = [
                'min' => '1.7',
                'max' => _PS_VERSION_
            ];
            $this->bootstrap = true;

            parent::__construct();

            $this->displayName = $this->l('Image Bullet');
            $this->description = $this->l('Add Image with Bullet for your products');

            $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        }

        public function install()
        {
            return parent::install() &&
                $this->installDB() &&
                $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle') &&
                $this->registerHook('displayProductExtraContent') &&
                $this->registerHook('actionFrontControllerSetMedia') &&
                $this->registerHook('actionAdminControllerSetMedia');
        }

        public function uninstall()
        {
            return parent::uninstall() &&
                $this->unregisterHook('displayAdminProductsMainStepLeftColumnMiddle') &&
                $this->unregisterHook('displayProductExtraContent') &&
                $this->unregisterHook('actionFrontControllerSetMedia') &&
                $this->unregisterHook('actionAdminControllerSetMedia');
        }

        public function installDB()
        {
            require_once(__DIR__ . '/sql/install.php');

            return true;
        }

        public function enable($force_all = false)
        {
            return parent::enable($force_all) &&
                $this->installDB() &&
                $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle') &&
                $this->registerHook('displayProductExtraContent') &&
                $this->registerHook('actionFrontControllerSetMedia') &&
                $this->registerHook('actionAdminControllerSetMedia');
        }

        public function disable($force_all = false)
        {
            return parent::disable($force_all) &&
                $this->unregisterHook('displayAdminProductsMainStepLeftColumnMiddle') &&
                $this->unregisterHook('displayProductExtraContent') &&
                $this->unregisterHook('actionFrontControllerSetMedia') &&
                $this->unregisterHook('actionAdminControllerSetMedia');
        }

        /**
         * Affichage des informations supplémentaires sur la fiche produit
         */
        public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
        {
            $product = new Product($params['id_product']);
            $languages = Language::getLanguages();

            $this->context->smarty->assign([
                'bullets'       =>  (json_encode(
                    array_map(
                        function ($v) {
                            return [
                                'id' => $v->id,
                                'image' => $v->image,
                                'title' => $v->title,
                                'bullets' => array_map(function ($v2) {
                                    return array_merge(
                                        $v2,
                                        [
                                            'text' => ($v2['text'])
                                        ]
                                    );
                                }, $v->decodedBullets())
                            ];
                        }, $product->getImageBullets()
                    )
                )),
                'add_url' => Link::getUrlSmarty(array(
                    'entity' => 'sf',
                    'route' => 'add_bullet',
                    'sf-params' => array(
                        'product_id' => $params['id_product'],
                    )
                )),
                'remove_url' => Link::getUrlSmarty(array(
                    'entity' => 'sf',
                    'route' => 'remove_bullet',
                    'sf-params' => array(
                        'id' => 'ID_BULLET',
                    )
                )),
                'save_url' => Link::getUrlSmarty(array(
                    'entity' => 'sf',
                    'route' => 'save_bullet',
                    'sf-params' => array(
                        'id' => 'ID_BULLET',
                    )
                ))
            ]);

            return $this->display(__FILE__, 'views/templates/hook/product_attributes.tpl');
        }

        public function hookActionAdminControllerSetMedia($params)
        {
            $this->context->controller->addCSS($this->_path . 'views/admin/css/style.css');
            $this->context->controller->addJS($this->_path . 'views/admin/js/admin.js');
        }

        public function hookDisplayProductExtraContent($params)
        {
            $product = $params['product'];
            $bullets = $product->getImageBullets();
            if(count($bullets) >= 1) {
                $productExtraContent = new ProductExtraContent();
                $productExtraContent->setTitle($this->l('Images'));
                $this->smarty->assign([
                    'bullets' => $bullets
                ]);
                $productExtraContent->setContent(
                    $this->display(__FILE__, 'views/templates/hook/displayProductExtraContent.tpl')
                );
                return array($productExtraContent);
            }
            return [];
        }


        public function hookActionFrontControllerSetMedia()
        {
            $this->context->controller->registerJavascript('modules-'.$this->name."_js", $this->_path .'views/front/js/main.js');
            $this->context->controller->registerStylesheet('modules-'.$this->name."_css", $this->_path .'views/front/css/style.css');
        }
    }

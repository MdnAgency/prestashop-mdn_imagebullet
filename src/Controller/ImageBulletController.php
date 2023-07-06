<?php
namespace ImageBullet\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

require_once _PS_MODULE_DIR_ . '/mdn_imagebullet/classes/ImageBulletModel.php';
class ImageBulletController extends FrameworkBundleAdminController {
    public function __construct()
    {
    }

    public function removeImageBullet($id) {
        $bullet = new \ImageBulletModel($id);
        $delete = $bullet->delete();

        return $this->json(
            [
                'success' => $delete
            ]
        );
    }


    public function saveImageBullet($id) {
        $content = \Tools::getValue("bullets");
        $title = \Tools::getValue('title');
        $bullet = new \ImageBulletModel($id);
        $bullet->bullets = $content;
        $bullet->title = $title;
        $save = $bullet->save();

        return $this->json(
            [
                'success' => $save
            ]
        );
    }

    public function createImageBullet($product_id) {

        foreach (['image_bullet'] as $img) {
            /** @var $uploadedFile UploadedFile */
            $uploadedFile =  new UploadedFile($_FILES[$img]['tmp_name'], $_FILES[$img]['name']);
            if(!empty($uploadedFile) && $uploadedFile->getBasename() != "") {
                if (!$uploadedFile->isValid()) {
                    $this->addFlash('danger', 'An error occurred while attempting to upload the file.');
                }
                else {
                    $dirName = md5($uploadedFile->getBasename());
                    $folder = _PS_CORE_IMG_DIR_ . 'image_bullet' . DIRECTORY_SEPARATOR;

                    if(!file_exists($folder))
                        mkdir($folder);

                    $file_name = $dirName .".". $uploadedFile->getClientOriginalExtension();
                    $path = $folder. $file_name;
                    if (!$uploadedFile->move($folder, $file_name)) {
                        $this->addFlash('danger', 'An error occurred while attempting to upload the file.');
                    }
                    $bullet = new \ImageBulletModel(0);
                    $bullet->id = 0;
                    $bullet->id_product = $product_id;
                    $bullet->image = $file_name;
                    $bullet->bullets = '[]';
                    $bullet->active = true;
                    $bullet->save();
                    $this->addFlash('success', 'Image mise en ligne');
                    return $this->json(
                        [
                            'success' => true,
                            'image' => $file_name,
                            'id' => $bullet->id
                        ]
                    );
                }
            }
        }

        return $this->json(
            [
                'success' => false
            ]
        );
    }
}
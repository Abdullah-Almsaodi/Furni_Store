<?php
class ProductManager
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function addProduct($name, $description, $price, $image)
    {
        // التحقق من الصورة
        $errors = $this->validateProductImage($image);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // إضافة المنتج بعد التحقق من الصورة
        $added = $this->productRepository->addProduct($name, $description, $price, $image['name']);
        if ($added) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to add product']];
        }
    }

    public function editProduct($id, $name, $description, $price, $image)
    {
        // التحقق من الصورة
        $errors = $this->validateProductImage($image);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // تحديث المنتج بعد التحقق من الصورة
        $updated = $this->productRepository->updateProduct($id, $name, $description, $price, $image['name']);
        if ($updated) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to update product']];
        }
    }

    public function deleteProduct($product_id)
    {
        $delete = $this->productRepository->deleteProduct($product_id);
        if ($delete) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to delete product']];
        }
    }

    public function getProducts()
    {
        return $this->productRepository->getAllProducts();
    }

    public function getProductById($id)
    {
        return $this->productRepository->getProductById($id);
    }

    // التحقق من الصورة
    private function validateProductImage($image)
    {
        $errors = [];

        if (!empty($image) && !empty($image['name'])) {
            $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $allowedExtensions = array("jpg", "jpeg", "png", "gif");

            // التحقق من نوع الصورة
            if (!in_array($imageFileType, $allowedExtensions)) {
                $errors['imageE'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF files are allowed.";
            } elseif ($image['error'] !== UPLOAD_ERR_OK) {
                // التحقق من أخطاء رفع الصورة
                $uploadErrors = array(
                    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                );

                $errorCode = $image['error'];
                if (isset($uploadErrors[$errorCode])) {
                    $errors['imageE'] = $uploadErrors[$errorCode];
                } else {
                    $errors['imageE'] = 'Unknown error occurred during file upload.';
                }
            }
        } else {
            $errors['imageE'] = "Image is required.";
        }

        return $errors;
    }
}

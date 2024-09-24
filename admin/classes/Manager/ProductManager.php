<?php
class ProductManager
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function addProduct($name, $description, $price, $cat_id, $image)
    {
        // Validate product data first
        // $errors = $this->validateProductData($name, $description, $price, $cat_id);
        // if (!empty($errors)) {
        //     return ['success' => false, 'errors' => $errors];
        // }

        // // Validate the image only if there are no data validation errors
        // $imageErrors = $this->validateProductImage($image);
        // if (!empty($imageErrors)) {
        //     return ['success' => false, 'errors' => array_merge($errors, $imageErrors)];
        // }

        // Proceed to add product
        $added = $this->productRepository->addProduct($name, $description, $price, $cat_id, $image);
        if ($added) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to add product']];
        }
    }


    public function editProduct($id, $name, $description, $price, $cat_id, $image)
    {
        // // التحقق من الصورة
        // $errors = $this->validateProductImage($image);
        // if (!empty($errors)) {
        //     return ['success' => false, 'errors' => $errors];
        // }

        // // Validate product data
        // $errors = $this->validateProductData($name, $description, $price, $cat_id);

        // if (!empty($errors)) {
        //     return ['success' => false, 'errors' => $errors];
        // }

        // Proceed to update product
        $updated = $this->productRepository->updateProduct($id, $name, $description, $price, $cat_id, $image);
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
    public function validateProductImage($image)
    {
        $errors = [];

        if (!empty($image) && !empty($image['name'])) {
            $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $allowedExtensions = array("jpg", "jpeg", "png", "gif");

            // Check image type
            if (!in_array($imageFileType, $allowedExtensions)) {
                $errors['image'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF files are allowed.";
            } elseif ($image['error'] !== UPLOAD_ERR_OK) {
                // Check upload errors
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
                    $errors['image'] = $uploadErrors[$errorCode];
                } else {
                    $errors['image'] = 'Unknown error occurred during file upload.';
                }
            } else {
                // Check image size (maximum 5MB)
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                if ($image['size'] > $maxFileSize) {
                    $errors['image'] = 'The image file is too large. Maximum size is 5MB.';
                }
            }
        } else {
            $errors['image'] = "Image is required.";
        }

        return $errors;
    }

    public function validateProductData($name, $description, $price, $cat_id)
    {
        $errors = [];

        // Validate name
        if (empty($name)) {
            $errors['name'] = "Name is required";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $errors['name'] = "Only letters and white space allowed";
        }

        // Validate price
        if (empty($price)) {
            $errors['price'] = "Price is required";
        } elseif (!is_numeric($price)) {
            $errors['price'] = "Only numeric values are allowed";
        }

        // Validate category ID
        if (empty($cat_id)) {
            $errors['cate'] = "Category is required";
        }

        // Validate description
        if (empty($description)) {
            $errors['description'] = "Description is required";
        } elseif (!preg_match("/^[a-zA-Z0-9\s.,'-]*$/", $description)) {
            $errors['description'] = "Only letters, numbers, whitespace, commas, apostrophes, and hyphens allowed";
        }

        return $errors;
    }
}

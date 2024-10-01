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
        $errors = []; // Initialize an empty array for errors

        // Image validation
        $imageErrors = $this->validateProductImage($image);
        if (!empty($imageErrors)) {
            $errors = array_merge($errors, $imageErrors); // Collect image errors
        }

        // Validate product data
        $productErrors = $this->validateProductData($name, $description, $price, $cat_id);
        if (!empty($productErrors)) {
            $errors = array_merge($errors, $productErrors); // Collect product data errors
        }

        // If there are any errors, return them
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Upload the image
        $imagePath = $this->uploadProductImage($image);

        // Add product to the repository
        $added = $this->productRepository->addProduct($name, $description, $price, $cat_id, $imagePath);
        return $added ? ['success' => true] : ['success' => false, 'errors' => ['general' => 'Failed to add product']];
    }

    public function editProduct(int $id, string $name, string $description, float $price, int $cat_id, array $image = null): array
    {
        $errors = []; // مصفوفة لتخزين الأخطاء

        // التحقق من البيانات
        $productErrors = $this->validateProductData($name, $description, $price, $cat_id);
        if (!empty($productErrors)) {
            $errors = array_merge($errors, $productErrors); // اجمع الأخطاء إذا وجدت
        }

        // التحقق من الصورة (إذا تم تقديم صورة جديدة)
        $imagePath = null;
        if ($image && !empty($image['name'])) {
            $imageErrors = $this->validateProductImage($image);
            if (!empty($imageErrors)) {
                $errors = array_merge($errors, $imageErrors); // اجمع الأخطاء إذا وجدت
            } else {
                // رفع الصورة
                $imagePath = $this->uploadProductImage($image);
                if ($imagePath === null) {
                    $errors['image'] = "Failed to upload image.";
                }
            }
        } else {
            // استخدم الصورة القديمة إذا لم يتم تقديم صورة جديدة
            $currentProduct = $this->productRepository->getProductById($id);
            $imagePath = $currentProduct['image']; // استخدم الصورة القديمة
        }

        // إذا كانت هناك أخطاء، أعدها
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // تحديث المنتج في قاعدة البيانات
        $updated = $this->productRepository->updateProduct($id, $name, $description, $price, $cat_id, $imagePath);
        return $updated ? ['success' => true] : ['success' => false, 'errors' => ['general' => 'Failed to update product']];
    }

    public function deleteProduct($product_id)
    {
        $deleted = $this->productRepository->deleteProduct($product_id);
        return $deleted ? ['success' => true] : ['success' => false, 'errors' => ['general' => 'Failed to delete product']];
    }

    public function getProducts()
    {
        return $this->productRepository->getAllProducts();
    }

    public function getProductById($id)
    {
        return $this->productRepository->getProductById($id);
    }

    // Validate Image
    private function validateProductImage($image)
    {
        $errors = [];
        if (!empty($image) && !empty($image['name'])) {
            $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

            // Check image type
            if (!in_array($imageFileType, $allowedExtensions)) {
                $errors['image'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF files are allowed.";
            } elseif ($image['error'] !== UPLOAD_ERR_OK) {
                // Handle upload errors
                $errors['image'] = $this->getUploadErrorMessage($image['error']);
            } else {
                // Check image size (e.g., should not exceed 5MB)
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

    // Validate Product Data
    private function validateProductData($name, $description, $price, $cat_id)
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
            $errors['cat_id'] = "Category is required";
        }

        // Validate description
        if (empty($description)) {
            $errors['description'] = "Description is required";
        } elseif (!preg_match("/^[a-zA-Z0-9\s.,'-]*$/", $description)) {
            $errors['description'] = "Only letters, numbers, whitespace, commas, apostrophes, and hyphens allowed";
        }

        return $errors;
    }

    // Handle image upload
    private function uploadProductImage($image)
    {
        // مسار المجلد الذي سيتم حفظ الصورة فيه
        $targetDir = "../upload/";

        // استخدام اسم الصورة الأصلي
        $imageName = basename($image['name']);

        // المسار الكامل الذي سيتم حفظ الصورة فيه
        $targetFile = $targetDir . $imageName;

        // نقل الصورة إلى المسار المستهدف
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            // إرجاع اسم الصورة فقط لحفظه في قاعدة البيانات
            return $imageName;
        } else {
            return null;
        }
    }


    // Error handling for file upload issues
    private function getUploadErrorMessage($errorCode)
    {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
        ];

        return $uploadErrors[$errorCode] ?? 'Unknown error occurred during file upload.';
    }
}

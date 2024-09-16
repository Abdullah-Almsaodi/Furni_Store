
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
        $errors = $this->validateProductData($name, $description, $price, $image);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $added = $this->productRepository->addProduct($name, $description, $price, $image);
        if ($added) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to add product']];
        }
    }

    public function editProduct($id, $name, $description, $price, $image)
    {
        $errors = $this->validateProductData($name, $price, $id, $description);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $updated = $this->productRepository->updateProduct($id, $name, $description, $price, $image);
        if ($updated) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to update product']];
        }
    }

    public function deleteProduct($id)
    {

        $delete = $this->productRepository->deleteProduct($id);

        if ($delete == 1) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to delete product']];
        }
    }

    public function getProdcuts()
    {
        return $this->productRepository->getAllProducts();
    }

    public function getProductById($id)
    {
        return $this->productRepository->getProductById($id);
    }

    private function validateProductData($name, $price, $cat_id,  $description,)
    {
        $errors = [];

        if (empty($_POST["name"])) {
            $errors['nameE'] = " Name is required";
        } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $errors['nameE'] = "Only letters and white space allowed";
            }
        }



        if (empty($_POST["price"])) {
            $errors['priceE'] = "price Number is required";
        } else {
            $price = test_input($_POST["price"]);
            // check if phone only contains numbers
            if (!is_numeric($price)) {
                $errors['priceE'] = "Only numeric values allowed";
            }
        }

        if (empty($_POST["cat_id"])) {
            $errors['cateE'] = " Role is required";
        } else {
            $cat_id = test_input($_POST["cat_id"]);
        }


        if (empty($_POST["description"])) {
            $errors['descriptionE'] = "About me is required";
        } else {
            $description = test_input($_POST["description"]);
            // check if about me only contains letters, whitespace, and numbers
            if (!preg_match("/^[a-zA-Z0-9\s.,'-]*$/", $description)) {
                $errors['descriptionE'] = "Only letters, numbers, whitespace, commas, apostrophes, and hyphens allowed in the about me field";
            }
        }

        return $errors;
    }

    private function validateProductImage($name, $price, $cat_id,  $description,)
    {
        $errors = [];

        // Validate image
        if (!empty($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = array("jpg", "jpeg", "png", "gif");

            // Check if the uploaded file is a valid image file
            if (!in_array($imageFileType, $allowedExtensions)) {
                $errors['imageE'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF files are allowed.";
            } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                // Check for upload errors
                $uploadErrors = array(
                    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                );

                $errorCode = $_FILES['image']['error'];
                if (isset($uploadErrors[$errorCode])) {
                    $errors['imageE'] = $uploadErrors[$errorCode];
                } else {
                    $errors['imageE'] = 'Unknown error occurred during file upload.';
                }
            }
        } else {
            $errors['imageE'] = "Image is required";
        }
        return $errors;
    }
}

<?php
class CategoryManager
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function addCategory($name, $description)
    {
        $errors = $this->validateCategoryData($name, $description);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $added = $this->categoryRepository->addCategory($name, $description);
        if ($added) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to add category']];
        }
    }

    public function editCategory($id, $name, $description)
    {
        $errors = $this->validateCategoryData($name, $description);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $updated = $this->categoryRepository->updateCategory($id, $name, $description);
        if ($updated) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to update category']];
        }
    }

    public function deleteCategory($id)
    {

        $delete = $this->categoryRepository->deleteCategory($id);

        if ($delete == 1) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to delete category']];
        }
    }

    public function getCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function getCategoryById($id)
    {
        return $this->categoryRepository->getCategoryById($id);
    }

    private function validateCategoryData($name, $description)
    {
        $errors = [];
        if (empty($name)) {
            $errors['cname'] = "Category Name is required";
        } elseif (is_numeric($name)) {
            $errors['cname'] = "Enter String Name of Category";
        }
        if (empty($description)) {
            $errors['cdescription'] = "Category description is required ";
        }
        return $errors;
    }
}

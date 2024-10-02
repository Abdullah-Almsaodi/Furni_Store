<?php
// CategoryManagerTest.php

use PHPUnit\Framework\TestCase;

require_once '../admin/classes/Manager/CategoryManager.php';  // Adjust the path if necessary
require_once '../admin/classes/Repository/CategoryRepository.php';  // Make sure to include the repository if needed


class CategoryManagerTest extends TestCase
{
    private $categoryManager;
    private $mockCategoryRepository;

    protected function setUp(): void
    {
        // Create a mock for the CategoryRepository
        $this->mockCategoryRepository = $this->createMock(CategoryRepository::class);

        // Inject the mock into CategoryManager
        $this->categoryManager = new CategoryManager($this->mockCategoryRepository);
    }

    public function testAddCategorySuccess()
    {
        // Arrange
        $name = "Test Category";
        $description = "Description for test category";

        $this->mockCategoryRepository->method('addCategory')->willReturn(true);

        // Act
        $result = $this->categoryManager->addCategory($name, $description);

        // Assert
        $this->assertTrue($result['success']);
    }

    public function testAddCategoryValidationFailure()
    {
        // Act
        $result = $this->categoryManager->addCategory("", "");

        // Assert
        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('errors', $result);
        $this->assertCount(2, $result['errors']);
    }

    public function testEditCategorySuccess()
    {
        // Arrange
        $id = 1;
        $name = "Updated Category";
        $description = "Updated description";

        $this->mockCategoryRepository->method('updateCategory')->willReturn(true);

        // Act
        $result = $this->categoryManager->editCategory($id, $name, $description);

        // Assert
        $this->assertTrue($result['success']);
    }

    public function testDeleteCategorySuccess()
    {
        // Arrange
        $id = 1;

        $this->mockCategoryRepository->method('deleteCategory')->willReturn(true);

        // Act
        $result = $this->categoryManager->deleteCategory($id);

        // Assert
        $this->assertTrue($result['success']);
    }
}

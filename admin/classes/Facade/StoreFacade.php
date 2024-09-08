<?php
// StoreFacade.php
class StoreFacade
{
    private $productManager;
    private $userManager;

    public function __construct($productManager, $userManager)
    {
        $this->productManager = $productManager;
        $this->userManager = $userManager;
    }

    public function performComplexAction()
    {
        // Combine logic from multiple managers
        $this->userManager->someAction();
        $this->productManager->anotherAction();
    }
}

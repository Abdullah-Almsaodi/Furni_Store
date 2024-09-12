<?php

// Include necessary files and initialize required objects
include('../templates/header.php');
require_once '../pages/config.php';
require_once '../classes/Database.php';
require_once '../classes/UserManager.php';
require_once '../classes/CategoryManager.php';
require_once '../classes/ProductManager.php';
require_once '../classes/Repository/ProductRepository.php';
require_once '../classes/Repository/CategoryRepository.php';
require_once '../classes/Repository/UserRepository.php';
require_once '../classes/PasswordService.php';
require_once '../classes/UserValidator.php';

// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();

// Initialize services
$passwordService = new PasswordService();
$userValidator = new UserValidator();

// Initialize repositories with dependencies
$userRepository = new UserRepository($conn);
$productRepository = new ProductRepository($dbInstance);
$categoryRepository = new CategoryRepository($dbInstance);

// Initialize managers with repositories
$userManager = new UserManager($userRepository, $passwordService, $userValidator);
$categoryManager = new CategoryManager($categoryRepository,);
$productManager = new ProductManager($dbInstance);

// Your application logic goes here

?>

<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fa fa-dashboard "></i> Dashboard</h2>

            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-red set-icon">
                        <i class="fa fa-users"></i>
                    </span>
                    <div class="text-box">
                        <p class="main-text">
                            <?php

                            $userNumber = $userManager->getUsers();
                            echo  count($userNumber);

                            ?>


                            Users</p>
                        <br>
                        <br>
                    </div>
                    <a href="users.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-green set-icon">
                        <i class="fa fa-tasks"></i>
                    </span>
                    <div class="text-box">
                        <p class="main-text"> <?php

                                                $productNumber = $productManager->getProducts();
                                                echo  count($productNumber);


                                                ?> Products</p>
                        <br>
                        <br>

                    </div>
                    <a href="products.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-blue set-icon">
                        <i class="fa fa-table"></i>
                    </span>
                    <div class="text-box">
                        <p class="main-text">
                            <?php
                            $categoryNumber = $categoryManager->getCategories();
                            echo  count($categoryNumber);



                            ?> Categories</p>
                        <br>
                        <br>
                    </div>
                    <a href="categories.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>



        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->

<?php
include('../templates/Footer.php');
?>
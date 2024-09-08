<?php
// Include necessary files and initialize required objects
include('upload/h2.php');
require_once 'config.php'; // Database configuration
require_once 'classes/Database.php';
require_once 'classes/UserManager.php';
require_once 'classes/CategoryManager.php';
require_once 'classes/Repository/UserRepository.php';





$db = new Database();
$conn = $db->connect();
$userRepository = new UserRepository($conn);
$userManager = new UserManager($userRepository);
$categoryManager = new CategoryManager($db);
$productManager = new ProductManager($db);



?>


<main>
    <div class="container-fluid px-4">
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
                    <a href="users1.php">
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
    </div>
</main>


<?php
include('upload/f2.php');
?>
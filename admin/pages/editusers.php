<?php
// Include necessary files and initialize required objects
include('../templates/header.php');
require_once 'config.php';
require_once '../classes/Database.php';
require_once '../classes/Manager/UserManager.php';
require_once '../classes/Repository/UserRepository.php';



// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();


// Initialize repositories with dependencies
$userRepository = new UserRepository($conn);
$userManager = new UserManager($userRepository);





if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {
    $id = $_GET['id'];
    $user = $userManager->getUserById($id);
}

?>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fa fa-users"></i> Users <?php echo $id; ?></h2>


            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-md-8">
                <!-- Form Elements -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus-circle"></i> Update User
                    </div>
                    <?php
                    // define variables and set to empty values
                    $username = $email = $role_type = $active = $password = $password1 =  "";
                    $errors = array();
                    $successMessage = '';
                    if (isset($_POST['submitUser'])) {

                        if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {


                            $username = $_POST['name'] ?? '';
                            $email = $_POST['email'] ?? '';
                            $password = $_POST['password'] ?? '';
                            $password1 = $_POST['password1'] ?? '';
                            $role = isset($_POST['role']) ? (int)$_POST['role'] : 0; // Ensure role is an integer
                            $active = $_POST['active'] ?? '';

                            $result = $userManager->updateUser($id, $username, $email, $password, $password, $role, $active);

                            if ($result['success']) {

                                $_SESSION['message'] = "User Update successfully";
                                header('Location: users.php');
                            } else {
                                $errors = $result['errors'];
                            }
                        }
                    }














                    ?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="modal fade" id="successModal" tabindex="-1" role="dialog"
                                aria-labelledby="successModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="successModalLabel">Success</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            New user and role have been added.

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <?php if ($successMessage): ?>

                                <div class='alert alert-success'><?php echo $successMessage; ?></div>
                                <?php endif; ?>

                                <?php if (isset($errors['general'])): ?>
                                <div class='alert alert-danger'><?php echo $errors['general']; ?>
                                </div>
                                <?php endif; ?>
                                <?php

                                $username = $user['username'];
                                $email = $user['email'];
                                $password = $user['password']; // Assuming the password column name is 'password'
                                $role_id = $user['role_id']; // Assuming the password column name is 'password'
                                $active = $user['is_active']; // Assuming the password column name is 'password'

                                ?>

                                <form role="form" method="post">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="<?php echo $username; ?>"
                                            placeholder="Please enter your name" class="form-control" />
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['username'])) echo $errors['username'];
                                            ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?php echo $email; ?>" placeholder="Please enter email" />
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['email'])) echo $errors['email'];
                                            ?>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control"
                                            value="<?php echo $password; ?>" placeholder="Please enter password">
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['password'])) echo $errors['password'];
                                            ?>
                                        </span>
                                    </div>


                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" name="password1" class="form-control"
                                            value="<?php echo $password; ?>"
                                            placeholder="Please enter confirm password">
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['password'])) echo $errors['password'];
                                            elseif (isset($errors['password1'])) {
                                                echo $errors['password1'];
                                            }
                                            ?>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label>User Type</label>
                                        <select name="role" class="form-control">
                                            <option value="" selected disabled>Select Role</option>
                                            <option value="1" <?php echo ($role_id == 1) ? 'selected ' : ''; ?>>
                                                Administrator</option>
                                            <option value="2" <?php echo ($role_id == 2) ? 'selected ' : ''; ?>>
                                                User</option>

                                        </select>
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['role_id'])) echo  $errors['role_id'];

                                            ?>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label>User Active</label>
                                        <select name="active" class="form-control">
                                            <option value="" selected disabled>Select Active</option>
                                            <option value="1" <?php echo ($active == 1) ? 'selected ' : ''; ?>>Active
                                            </option>
                                            <option value="2" <?php echo ($active == 2) ? 'selected ' : ''; ?>>
                                                Non_Active</option>
                                        </select>
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['active'])) echo $errors['active'];
                                            ?>
                                        </span>
                                    </div>
                                    <div style="float:right;">
                                        <button name="submitUser" type="submit" class="btn btn-primary">Update
                                            User</button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <hr />





            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
</div>

<!-- /. WRAPPER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- MORRIS CHART SCRIPTS -->
<script src="assets/js/morris/raphael-2.1.0.min.js"></script>
<script src="assets/js/morris/morris.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>
<!-- jQuery 3 -->
<!-- <script src=""></script> -->


</body>

</html>
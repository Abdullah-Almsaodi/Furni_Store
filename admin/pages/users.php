<?php
// Include necessary files and initialize required objects
include('../templates/header.php');
require_once '../pages/config.php';
require_once '../classes/Database.php';
require_once '../classes/UserManager.php';
require_once '../classes/CategoryManager.php';
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
$userManager = new UserManager($userRepository, $passwordService, $userValidator);

// echo '<script type="text/javascript">
// $(function() {
//     // Show Bootstrap modal
//     $("#successModal").modal("show");
// });
// </script>';

?>
<!-- Success Modal -->
<!-- <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
    aria-hidden="true">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->




<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fa fa-users"></i> Users</h2>


            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-md-8">
                <!-- Form Elements -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus-circle"></i> Add New User
                    </div>
                    <?php
                    // define variables and set to empty values
                    $name = $email = $password = $password1 = $role_type = $active = "";
                    $errors = [];
                    $successMessage = '';
                    $successM = '';
                    $err = '';



                    if (isset($_POST['submitUser'])) {

                        // Handle form submission for adding a new user


                        if ($_SERVER["REQUEST_METHOD"] == "POST") {

                            $username = $_POST['name'] ?? '';
                            $email = $_POST['email'] ?? '';
                            $password = $_POST['password'] ?? '';
                            $password1 = $_POST['password1'] ?? '';
                            $role = isset($_POST['role']) ? (int)$_POST['role'] : 0; // Ensure role is an integer

                            // Call the addUser method from UserManager
                            $result = $userManager->addUser($username, $email, $password, $password1, $role);

                            // Check if the user was added successfully or if there were errors
                            if ($result['success']) {

                                $successMessage = "User added successfully";
                            } else {

                                // Debugging: print errors if necessary
                                error_log('Add User Errors: ' . print_r($errors, true));
                                $errors = $result['errors'];
                                // $successMessage = "<div class='alert alert-danger'>Cannot add User </div>";
                            }
                        }
                    }


                    $users = $userManager->getUsers();





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
                                if (isset($_SESSION['message'])) : ?>
                                    <div class='alert alert-success'><?php echo $_SESSION['message'] ?></div>

                                <?php unset($_SESSION['message']);
                                endif; ?>

                                <form role="form" method="post">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="<?php echo $name; ?>"
                                            placeholder="Please Enter your Name " class="form-control" />
                                        <span style="color:red">
                                            <?php if (isset($errors['nameE'])): echo $errors['nameE'];
                                            endif; ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?php echo $email; ?>" placeholder="PLease Enter Eamil" />
                                        <span style="color:red">
                                            <?php if (isset($errors['emailE'])):
                                                echo $errors['emailE'];
                                            endif; ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Please Enter password">
                                        <span style="color:red">

                                            <?php if (isset($errors['passE'])):
                                                echo $errors['passE'];
                                            endif; ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" name="password1" class="form-control"
                                            placeholder="Please Enter confirm password">
                                        <span style="color:red">
                                            <?php if (isset($errors['passE'])):
                                                echo $errors['passE'];
                                            ?>
                                            <?php elseif (isset($errors['passEM'])):
                                                echo $errors['passEM'];
                                            endif; ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>User Type</label>
                                        <select name="role" class="form-control">
                                            <option value="" selected disabled>Select Role</option>
                                            <option value="1"
                                                <?php echo (isset($_POST['role']) && $_POST['role'] == 1) ? 'selected' : ''; ?>>
                                                Administrator</option>
                                            <option value="2"
                                                <?php echo (isset($_POST['role']) && $_POST['role'] == 2) ? 'selected' : ''; ?>>
                                                User</option>

                                        </select>
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['roleE'])) echo  $errors['roleE'];

                                            ?>
                                        </span>
                                    </div>
                                    <div style="float:right;">
                                        <button name="submitUser" type="submit" class="btn btn-primary">Add
                                            User</button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
                                    </div>

                            </div>
                            </form>



                        </div>

                    </div>
                </div>
            </div>

        </div>
        <hr />


        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-users"></i> Users
                    </div>

                    <?php
                    if (isset($_GET['action'], $_GET['id'])) {
                        $user_id = $_GET['id'];
                        switch ($_GET['action']) {
                            case "delete":

                                $delete = $userRepository->deleteUser($user_id);
                                if ($delete == 1) {
                                    // $successM = "User deleted successfully";
                                    $_SESSION['message'] = "User deleted successfully";
                                    echo " <script> window.open('users.php','_self');
                                    </script> ";
                                } else {
                                    $err = 'errors';
                                    // $errors = $delete['errors'];
                                }
                                break;

                            default:
                                $errors = $result['errors'];
                                break;
                        }
                    }

                    ?>
                    <div class="panel-body">

                        <?php if ($successM): ?>

                            <div class='alert alert-success'><?php echo $successM; ?></div>
                        <?php endif; ?>

                        <?php if (isset($errors['general'])): ?>
                            <div class='alert alert-danger'><?php echo $errors['general']; ?>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Is_active</th>
                                        <th>Role</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>




                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['password']); ?></td>
                                                <td><?php echo htmlspecialchars($user['is_active']); ?></td>
                                                <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                                                <td>
                                                    <a href="editusers.php?action=edit&id=<?php echo $user['user_id']; ?>"
                                                        class='btn btn-success action'>Edit</a>
                                                    <a href="?action=delete&id=<?php echo $user['user_id']; ?>"
                                                        class='delete btn btn-danger '>Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan='5'>No users found.</td>
                                        </tr>
                                    <?php endif; ?>





                                </tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!--End Advanced Tables -->

            </div>
            <!-- /. ROW  -->
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
</div>

<?php
include('../templates/Footer.php');
?>

<script>
    $(document).ready(function() {
        $('.delete').click(function() {
            return confirm('Are You Sure !!');
        });
    });
</script>
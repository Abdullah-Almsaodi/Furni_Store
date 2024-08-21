<?php
// Include necessary files and initialize required objects
include('upload/header.php');
require_once 'config.php'; // Database configuration
require_once 'classes/Database.php';
require_once 'classes/UserManager.php';
require_once 'classes/UserRepository.php';
require_once 'classes/RoleManager.php';
require_once 'classes/ProfileManager.php';
require_once 'classes/UserManagementFacade.php';

$db = new Database();
$conn = $db->connect();
$userRepository = new UserRepository($conn);
$userManager = new UserManager($userRepository);
$userFacade = new UserManagementFacade($userManager, new RoleManager($db), new ProfileManager($db));

// Handle different actions based on the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'register':
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password1 = $_POST['password1'];
            $role_type = $_POST['role_type'];

            // Validate the data
            $errors = $userManager->validateUserData($name, $email, $password, $password1, $role_type);

            if (empty($errors)) {
                try {
                    $userFacade->registerUser([
                        'username' => $name,
                        'email' => $email,
                        'password' => $password,
                        'role_type' => $role_type,
                    ]);
                    echo "User registered successfully!";
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                // Return or display errors
                foreach ($errors as $key => $error) {
                    echo $error . "<br>";
                }
            }
            break;

        case 'delete':
            $user_id = $_POST['user_id'];
            try {
                $userManager->deleteUser($user_id);
                echo "User deleted successfully!";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
            break;

        case 'updateRole':
            $user_id = $_POST['user_id'];
            $role_id = $_POST['role_id'];
            try {
                $userManager->updateUserRole($user_id, $role_id);
                echo "User role updated successfully!";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
            break;

        case 'reactivate':
            $user_id = $_POST['user_id'];
            try {
                $userManager->reactivateUser($user_id);
                echo "User reactivated successfully!";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
            break;

            // Add more cases as needed
    }
}

// If it's a GET request, perhaps for displaying user data
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $users = $userManager->getUsers();
//     foreach ($users as $user) {
//         echo "Username: " . htmlspecialchars($user['username']) . "<br>";
//         echo "Email: " . htmlspecialchars($user['email']) . "<br>";
//         echo "Role: " . htmlspecialchars($user['role_name']) . "<br>";
//         echo "Status: " . ($user['is_active'] ? 'Active' : 'Inactive') . "<br>";
//         echo "<hr>";
//     }
// }




echo '<script type="text/javascript">
$(function() {
    // Show Bootstrap modal
    $("#successModal").modal("show");
});
</script>';

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
                    $name = $email = $password = $password1 = $role = "";
                    $errors = array();
                    if (isset($_POST['submitUser'])) {

                        if (isset($_POST['submitUser'])) {




                            if (empty($_POST["name"])) {
                                $errors['nameE'] = " Name is required";
                            } else {
                                $name = test_input($_POST["name"]);
                                // check if name only contains letters and whitespace
                                if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                                    $errors['nameE'] = "Only letters and white space allowed";
                                }
                            }

                            if (empty($_POST["email"])) {
                                $errors['emailE'] = " Email is required";
                            } else {
                                $email = test_input($_POST["email"]);
                                // check if e-mail address is well-formed
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $errors['emailE'] = "Invalid email format";
                                }
                            }
                            if (empty($_POST["role"])) {
                                $errors['roleE'] = " Role is required";
                            } else {
                                $role = test_input($_POST["role"]);
                            }

                            if (empty($_POST["email"])) {
                                $errors['emailE'] = " Email is required";
                            } else {
                                $email = test_input($_POST["email"]);
                                // check if e-mail address is well-formed
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $errors['emailE'] = "Invalid email format";
                                }
                            }

                            if (empty($_POST["password"] || $_POST["password1"])) {
                                $errors['passE'] =  " Password is required";
                            } else {

                                $password = test_input($_POST["password"]);
                                $password1 = test_input($_POST["password1"]);

                                // check if Passwords do  match
                                if ($_POST["password"] != $_POST["password1"]) {
                                    $errors['passEM'] =  "Passwords do not match.";
                                }
                            }

                            // echo $role . "<br>";
                            // var_dump($role);

                            if (empty($errors)) {
                                $Users =  $UserManager->addUser($name, $email, $password, $password1, $role_type);
                            }
                        }
                    }




                    function test_input($data)
                    {
                        $data = trim($data);
                        $data = stripslashes($data);
                        $data = htmlspecialchars($data);
                        return $data;
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
                                <form role="form" method="post">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="<?php echo $name; ?>"
                                            placeholder="Please Enter your Name " class="form-control" />
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['nameE'])) echo  $errors['nameE']
                                            ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?php echo $email; ?>" placeholder="PLease Enter Eamil" />
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['emailE'])) echo  $errors['emailE']
                                            ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Please Enter password">
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['passE'])) echo  $errors['passE'];

                                            ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" name="password1" class="form-control"
                                            placeholder="Please Enter confirm password">
                                        <span style="color:red">
                                            <?php

                                            if (isset($errors['passE'])) echo  $errors['passE'];
                                            elseif (isset($errors['passEM'])) {
                                                echo  $errors['passEM'];
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>User Type</label>
                                        <select name="role" class="form-control">
                                            <option value="" selected disabled>Select Role</option>
                                            <option value="Admin">Administrator</option>
                                            <option value="User">User</option>
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
                        $id = $_GET['id'];
                        switch ($_GET['action']) {
                            case "delete":
                                $stm = $conn->prepare("delete from Users where user_id=:user_id");
                                $stm->execute(array("user_id" => $id));
                                if ($stm->rowCount() == 1) {
                                    echo "<div class='alert alert-success'> One Row Deleted</div>";
                                }
                                break;


                            default:
                                echo "ERROR";
                                break;
                        }
                    }


                    ?>
                    <div class="panel-body">
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

                                    <?php
                                    try {
                                        // Assume you have established a database connection and assigned it to the $db variable

                                        // Fetch the list of registered users from the database with their corresponding role types
                                        $query = "SELECT Users.*, Roles.role_name AS role_type 
                                            FROM Users 
                                            INNER JOIN Roles ON Users.role_id = Roles.role_id";

                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        // var_dump($users); // Debugging output
                                        if ($stmt->rowCount() > 0) {
                                            foreach ($users as $row) {

                                                $id = $row['user_id'];
                                                $name =  $row['username'];
                                                $email =  $row['email'];
                                                $password =  $row['password'];
                                                $is_active =  $row['is_active'];
                                                $role_type = $row['role_type'];

                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $id ?></td>
                                        <td><?php echo $name ?></td>
                                        <td><?php echo $email ?></td>
                                        <td><?php echo $password ?></td>
                                        <td><?php echo $is_active ?></td>
                                        <td><?php echo $role_type ?></td>
                                        <td>
                                            <a href="editusers.php?action=edit&id=<?php echo $id ?>"
                                                class='btn btn-success'>Edit</a>
                                            <a href="?action=delete&id=<?php echo $id ?>"
                                                class='delete btn btn-danger'>Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        } else {
                                            ?>
                                    <tr>
                                        <td colspan='5'>No users found.</td>
                                    </tr>
                                    <?php
                                        }
                                    } catch (PDOException $e) {
                                        echo "Database Error: " . $e->getMessage();
                                    }
                                    ?>
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
include('upload/footer.php');
?>

<script>
f
$(document).ready(function() {
    $('.delete').click(function() {
        return confirm('Are You Sure !!');
    });
});
</script>
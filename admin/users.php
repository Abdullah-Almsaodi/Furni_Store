    <?php
    // Include required files

    include('upload/header.php');
    require_once 'classes/Database.php';
    require_once 'classes/UserManager.php';



    // Create a new Database instance
    $db = new Database();
    $conn = $db->connect();

    // Create a new UserManager instance
    $UserManager = new UserManager($db);

    // Define variables and set to empty values
    $name = $email = $password = $password1 = $role_type = "";
    $errors = array();

    if (isset($_POST['submitUser'])) {
        // Input validation
        if (empty($_POST["name"])) {
            $errors['nameE'] = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $errors['nameE'] = "Only letters and white space allowed";
            }
        }

        if (empty($_POST["email"])) {
            $errors['emailE'] = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emailE'] = "Invalid email format";
            }
        }

        if (empty($_POST["role"])) {
            $errors['roleE'] = "Role is required";
        } else {
            $role_type = test_input($_POST["role"]);
        }

        if (empty($_POST["password"]) || empty($_POST["password1"])) {
            $errors['passE'] = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
            $password1 = test_input($_POST["password1"]);
            if ($password != $password1) {
                $errors['passEM'] = "Passwords do not match.";
            }
        }

        if (empty($errors)) {
            try {
                $UserManager->addUser($name, $email, $password, $password1, $role_type);
                echo '<script type="text/javascript">$(document).ready(function() { $("#successModal").modal("show"); });</script>';
            } catch (Exception $e) {
                $errors['dbE'] = $e->getMessage();
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

    echo '<script type="text/javascript">
        $(function() {
            // Show Bootstrap modal
            $("#successModal").modal("show");
        });
    </script>';

    ?>
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h2><i class="fa fa-users"></i> Users </h2>
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo  $_SESSION['message'] . '</p>';
                        unset($_SESSION['message']);
                    } ?>

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
                        <div class="panel-body">
                            <div class="row">
                                <div class="modal fade" id="successModal" tabindex="-1" role="dialog"
                                    aria-labelledby="successModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="successModalLabel">Success</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
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
                                            <input type="text" name="name"
                                                value="<?php echo htmlspecialchars($name); ?>"
                                                placeholder="Please Enter your Name" class="form-control" />
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['nameE'])) echo $errors['nameE'];
                                                ?>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="<?php echo htmlspecialchars($email); ?>"
                                                placeholder="Please Enter Email" />
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['emailE'])) echo $errors['emailE'];
                                                ?>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Please Enter Password" />
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['passE'])) echo $errors['passE'];
                                                ?>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" name="password1" class="form-control"
                                                placeholder="Please Enter Confirm Password" />
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['passE'])) echo $errors['passE'];
                                                elseif (isset($errors['passEM'])) echo $errors['passEM'];
                                                ?>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>User Type</label>
                                            <select name="role" class="form-control">
                                                <option value="" selected disabled>Select Role</option>
                                                <option value="1" <?php echo $role_type == '1' ? 'selected' : ''; ?>>
                                                    Administrator</option>
                                                <option value="2" <?php echo $role_type == '2' ? 'selected' : ''; ?>>
                                                    User</option>
                                            </select>
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['roleE'])) echo $errors['roleE'];
                                                ?>
                                            </span>
                                        </div>

                                        <div style="float:right;">
                                            <button name="submitUser" type="submit" class="btn btn-primary">Add
                                                User</button>
                                            <button type="reset" class="btn btn-danger">Cancel</button>
                                        </div>

                                    </form>
                                </div>

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
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Is Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            // Fetch the list of registered users from the database
                                            $query = "SELECT users.user_id, users.name, users.email, users.is_active, roles.role_name AS role_type 
                                            FROM users 
                                            INNER JOIN roles ON users.user_id = roles.role_id";

                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if (count($users) > 0) {
                                                foreach ($users as $row) {
                                                    $id = htmlspecialchars($row['user_id']);
                                                    $name = htmlspecialchars($row['name']);
                                                    $email = htmlspecialchars($row['email']);
                                                    $is_active = $row['is_active'];
                                                    $role_type = htmlspecialchars($row['role_type']);
                                        ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $id; ?></td>
                                                        <td><?php echo $name; ?></td>
                                                        <td><?php echo $email; ?></td>
                                                        <td><?php echo $role_type; ?></td>
                                                        <td>
                                                            <?php
                                                            if ($is_active == 1) {
                                                                echo "Active";
                                                            } elseif ($is_active == 2) {
                                                                echo "Non-Active";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <a href="editusers.php?action=edit&id=<?php echo $id; ?>"
                                                                class='btn btn-success'>Edit</a>
                                                            <a href="?action=delete&id=<?php echo $id; ?>"
                                                                class='btn btn-danger'>Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan='6'>No users found.</td>
                                                </tr>
                                        <?php
                                            }
                                        } catch (PDOException $e) {
                                            echo "<tr><td colspan='6'>Database Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                                        }
                                        ?>
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
        $(document).ready(function() {
            $('.delete').click(function() {
                return confirm('Are You Sure !!');
            });
        });
    </script>
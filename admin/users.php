    <?php

    include('upload/header.php');
    require('db_connect.php');


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
                        <?php
                        // define variables and set to empty values
                        $name = $email = $password = $password1 = $role = $active = $activebefoer = "";
                        $errors = array();
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


                            if (empty($_POST["active"])) {
                                $errors['activeE'] = " Active is required ";
                            } else {
                                $activebefoer = $_POST["active"];
                                $activebefoer = 0;
                                $active = test_input($activebefoer);
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


                            if (empty($errors)) {

                                // start prepare after all checks
                                $query = "SELECT user_id FROM users WHERE email = :email";
                                $stmt = $db->prepare($query);
                                $stmt->bindParam(':email', $email);
                                $stmt->execute();

                                if ($stmt->rowCount() > 0) {
                                    $errors['emailE'] =  "A user with that email already exists.";
                                } else {
                                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                                    $activation_token = bin2hex(random_bytes(16));

                                    $query = "INSERT INTO users (name, email, password,is_active,role_id, activation_token) VALUES (:name, :email, :password,:is_active,:role_id, :activation_token)";
                                    $stmt = $db->prepare($query);
                                    $stmt->bindParam(':name', $name);
                                    $stmt->bindParam(':email', $email);
                                    $stmt->bindParam(':password', $hashedPassword);
                                    $stmt->bindParam(':is_active', $active);
                                    $stmt->bindParam(':role_id', $role);
                                    $stmt->bindParam(':activation_token', $activation_token);
                                    $stmt->execute();




                                    echo "<div class='alert alert-success'>One Row   Insert </div>";


                                    echo "<script>
                                        window.open('users.php','_self');
                                    
                                            </script> 
                                            ";
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
                                <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
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
                                </div>


                                <div class="col-md-12">
                                    <form role="form" method="post">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Please Enter your Name " class="form-control" />
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['nameE'])) echo  $errors['nameE']
                                                ?>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="PLease Enter Eamil" />
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['emailE'])) echo  $errors['emailE']
                                                ?>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" placeholder="Please Enter password">
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['passE'])) echo  $errors['passE'];

                                                ?>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" name="password1" class="form-control" placeholder="Please Enter confirm password">
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
                                                <option value="1">Administrator</option>
                                                <option value="2">User</option>
                                            </select>
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['roleE'])) echo  $errors['roleE'];

                                                ?>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <label>User Active</label>
                                            <select name="active" class="form-control">
                                                <option value="" selected disabled>Select Active</option>
                                                <option value="1">Active</option>
                                                <option value="2">Non_Active</option>
                                            </select>
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['activeE'])) echo  $errors['activeE'];

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

                        <?php
                        if (isset($_GET['action'], $_GET['id'])) {
                            $id = $_GET['id'];
                            switch ($_GET['action']) {
                                case "delete":
                                    $stm = $db->prepare("delete from Users where user_id=:user_id");
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
                                            //                                 $query = "SELECT * from Users.*, Roles.type AS role_type 
                                            // FROM Users 
                                            // INNER JOIN Roles ON Users.user_id = Roles.role_id";


                                            $query = "SELECT * from Users";

                                            $stmt = $db->prepare($query);
                                            $stmt->execute();
                                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            // var_dump($users); // Debugging output
                                            if ($stmt->rowCount() > 0) {
                                                foreach ($users as $row) {

                                                    $id = $row['user_id'];
                                                    $name =  $row['name'];
                                                    $email =  $row['email'];
                                                    $password =  $row['password'];
                                                    $is_active =  $row['is_active'];
                                                    $role_type = $row['role_id'];

                                        ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $id ?></td>
                                                        <td><?php echo $name ?></td>
                                                        <td><?php echo $email ?></td>
                                                        <td><?php echo $password ?></td>
                                                        <td>
                                                            <?php
                                                            if ($is_active == 1) {
                                                                echo "Active";
                                                            } elseif ($is_active == 2) {
                                                                echo "Non-Active";
                                                            } ?>
                                                        </td>
                                                        <td><?php echo $role_type ?></td>
                                                        <td>
                                                            <a href="editusers.php?action=edit&id=<?php echo $id ?>" class='btn btn-success'>Edit</a>
                                                            <a href="?action=delete&id=<?php echo $id ?>" class='delete btn btn-danger'>Delete</a>
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
        $(document).ready(function() {
            $('.delete').click(function() {
                return confirm('Are You Sure !!');
            });
        });
    </script>
    <?php
    include('upload/header.php');
    require('db_connect.php');


    echo '<script type="text/javascript">
   
        $(function() {
            // Show Bootstrap modal
            $("#successModal").modal("show");
        });
   
    </script>';



    if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {
        $id = $_GET['id'];
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
                        $name = $email = $role = $active = "";
                        $errors = array();

                        if (isset($_POST['submitUser'])) {

                            if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {





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




                                if (empty($_POST["active"])) {
                                    $errors['activeE'] = " Active is required ";
                                } else {


                                    $active = test_input($_POST["active"]);
                                }




                                if (empty($errors)) {


                                    // start prepare after all checks

                                    // start prepare after all checks
                                    $query = "SELECT user_id FROM users WHERE email = :email";
                                    $stmt = $db->prepare($query);
                                    $stmt->bindParam(':email', $email);
                                    $stmt->execute();

                                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                                    $activation_token = bin2hex(random_bytes(16));





                                    $query = " UPDATE Users set name=? , email=? ,password=? ,role_id=? ,	is_active=? where user_id=?";

                                    $stmt = $db->prepare($query);
                                    $stmt->execute(array($name, $email, $hashedPassword, $role, $active, $id));
                                    if ($stmt->rowCount()) {
                                        $_SESSION['message'] = "<div class='alert alert-success'>One Row   Insert </div>";
                                        echo "<script>
                           
                                        window.open('users.php','_self');
                                        </script> ";
                                    } else {
                                        echo "<div class='alert alert-danger'>One Row  not Updated </div>";
                                    }
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
                                    <?php
                                    $stmt = $db->prepare("SELECT * FROM users WHERE user_id=:user_id");
                                    $stmt->execute(array("user_id" => $id));
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $name = $row['name'];
                                    $email = $row['email'];
                                    $password = $row['password']; // Assuming the password column name is 'password'

                                    ?>

                                    <form role="form" method="post">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" value="<?php echo $name; ?>"
                                                placeholder="Please enter your name" class="form-control" />
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['nameE'])) echo $errors['nameE'];
                                                ?>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="<?php echo $email; ?>" placeholder="Please enter email" />
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['emailE'])) echo $errors['emailE'];
                                                ?>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control"
                                                value="<?php echo $password; ?>" placeholder="Please enter password">
                                            <span style="color:red">
                                                <?php
                                                if (isset($errors['passE'])) echo $errors['passE'];
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
                                                if (isset($errors['passE'])) echo $errors['passE'];
                                                elseif (isset($errors['passEM'])) {
                                                    echo $errors['passEM'];
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
                                                if (isset($errors['roleE'])) echo $errors['roleE'];
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
                                                if (isset($errors['activeE'])) echo $errors['activeE'];
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
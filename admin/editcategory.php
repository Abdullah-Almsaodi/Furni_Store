<?php

include('upload/header.php');
require('db_connect.php');
?>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fa fa-tasks"></i> Categories</h2>

            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-md-8">
                <!-- Form Elements -->

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus-circle"></i> Edit Category
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php
                            if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {
                                $id = $_GET['id'];
                                $stm = $db->prepare("select * from categories where cat_id=:catid");
                                $stm->execute(array("catid" => $id));


                                if ($stm->rowCount()) {


                                    $name = $description = "";
                                    $errors = array();
                                    if (isset($_POST['submit'])) {


                                        if (empty($_POST["name"])) {
                                            $errors['nameE'] = " Name is required";
                                        } else {
                                            $name = test_input($_POST["name"]);
                                            // check if name only contains letters and whitespace
                                            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                                                $errors['nameE'] = "Only letters and white space allowed";
                                            }
                                        }





                                        if (empty($_POST["description"])) {
                                            $errors['descriptionE'] = "description me is required";
                                        } else {
                                            $description = test_input($_POST["description"]);
                                            // check if about me only contains letters, whitespace, and numbers
                                            if (!preg_match("/^[a-zA-Z0-9\s.,'-]*$/", $description)) {
                                                $errors['descriptionE'] = "Only letters, numbers, whitespace, commas, apostrophes, and hyphens allowed in the about me field";
                                            }
                                        }


                                        if (empty($errors)) {
                                            $sql = "update categories set name=? , description=? where cat_id=? ";
                                            $stm = $db->prepare($sql);
                                            $stm->execute(array($name, $description, $id));
                                            if ($stm->rowCount()) {
                                                $_SESSION['message'] = "<div class='alert alert-success'>One Row   Insert </div>";
                                                echo "<script>
                                       
                                        window.open('categories.php','_self');
                                         </script> 
                                        ";
                                            } else {
                                                echo "<div class='alert alert-danger'>One Row  not Updated </div>";
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



                                    foreach ($stm->fetchAll() as $row) {
                                        $id = $row['cat_id'];
                                        $name = $row['name'];
                                        $description = $row['description'];



                            ?>
                                        <div class="col-md-12">
                                            <form role="form" method="post">
                                                <input type="hidden" name="id" value="<?php echo $id ?>">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" placeholder="Please Enter your Name " class="form-control" value="<?php echo $name ?>" name="name" />
                                                    <?php if (isset($errors['cname'])) echo $errors['cname'] ?>
                                                    <?php if (isset($errors['cnameNumber'])) echo $errors['cnameNumber'] ?>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea placeholder="Please Enter Description" class="form-control" rows="3" cols="30" name='description'> <?php echo $description ?> </textarea>
                                                </div>

                                                <div style="float:right;">
                                                    <button type="submit" name="submit" class="btn btn-primary">Edit
                                                        Category</button>
                                                    <button type="reset" class="btn btn-danger">Cancel</button>
                                                </div>

                                        </div>
                                        </form>
                            <?php }
                                }
                            } ?>
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


<?php
include('upload/footer.php');
?>
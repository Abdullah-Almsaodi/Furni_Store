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
                        <i class="fa fa-plus-circle"></i> Add New Category
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php
                            if (isset($_POST['submit'])) {
                                $name = trim($_POST['name']);
                                $description = trim(($_POST['description']));
                                $errors = array();
                                if (empty($name)) {
                                    $errors['cname'] = "<div style='color:red'>Enter Name of Category</div>";
                                } elseif (is_numeric($name)) {
                                    $errors['cnameNumber'] = "<div style='color:red'>Enter String Name of Category</div>";
                                } else {
                                    $sql = "insert into categories (name,description) values (?,?) ";
                                    $stm = $db->prepare($sql);
                                    $stm->execute(array($name, $description));
                                    if ($stm->rowCount()) {
                                        echo "<div class='alert alert-success'>One Row Inserted </div>";
                                    } else {
                                        echo "<div class='alert alert-danger'>One Row  not Inserted </div>";
                                    }
                                }
                            }

                            ?>
                            <div class="col-md-12">
                                <form role="form" method="post">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" placeholder="Please Enter your Name " class="form-control" name="name" />
                                        <?php if (isset($errors['cname'])) echo $errors['cname'] ?>
                                        <?php if (isset($errors['cnameNumber'])) echo $errors['cnameNumber'] ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>

                                        <textarea placeholder="Please Enter Description" class="form-control" cols="30" rows="3" name='description'></textarea>
                                    </div>

                                    <div style="float:right;">
                                        <button type="submit" name="submit" class="btn btn-primary">Add
                                            Category</button>
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
                    <div c lass="panel-heading">
                        <i class="fa fa-tasks"></i> Categories
                    </div>
                    <?php
                    if (isset($_GET['action'], $_GET['id'])) {
                        $id = $_GET['id'];
                        switch ($_GET['action']) {
                            case "delete":
                                $stm = $db->prepare("delete from categories where cat_id=:catid");
                                $stm->execute(array("catid" => $id));
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
                            <table class="table table-striped table-bordered table-hover " id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stm = $db->prepare("select * from categories");
                                    $stm->execute();
                                    if ($stm->rowCount()) {
                                        foreach ($stm->fetchAll() as $row) {
                                            $id = $row['cat_id'];
                                            $name = $row['name'];
                                            $description = $row['description']
                                    ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $id  ?></td>
                                                <td><?php echo $name ?></td>
                                                <td><?php echo $description ?></td>
                                                <td>
                                                    <a href="editcategory.php?action=edit&id=<?php echo $id ?>" class='btn btn-success'>Edit</a>
                                                    <a href="?action=delete&id=<?php echo $id ?>" class='delete btn btn-danger'>Delete</a>

                                                </td>
                                            </tr>
                                        <?php  }
                                    } else { ?>

                                        <div class='alert alert-danger'>Not Row </div>
                                    <?php } ?>
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
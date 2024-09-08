<?php
include('upload/header.php');
require_once 'config.php'; // Database configuration
require_once 'classes/CategoryManager.php';
require_once 'classes/Database.php';

$db = new Database();

$categoryManager = new CategoryManager($db);

$categories = $categoryManager->getCategories();



?>


<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fa fa-tasks"></i> Categories</h2>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus-circle"></i> Add New Category
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php
                            $successMessage = $successM = $err = '';

                            $errors = [];
                            if (isset($_POST['submit'])) {
                                // Add the category using the CategoryManager
                                $result = $categoryManager->addCategory($_POST['name'], $_POST['description']);

                                if ($result['success']) {
                                    $successMessage = "User added successfully";
                                } else {
                                    $errors = $result['errors'];
                                }
                            }
                            ?>

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
                                        <input type="text" placeholder="Please Enter your Name " class="form-control"
                                            name="name" />

                                        <span style="color:red">
                                            <?php if (isset($errors['cname'])): echo $errors['cname'];
                                            endif; ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea placeholder="Please Enter Description" class="form-control" cols="30"
                                            rows="3" name='description'></textarea>

                                        <span style="color:red">
                                            <?php if (isset($errors['cdescription'])): echo $errors['cdescription'];
                                            endif; ?>
                                        </span>
                                    </div>
                                    <div style="float:right;">
                                        <button type="submit" name="submit" class="btn btn-primary">Add
                                            Category</button>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tasks"></i> Categories
                    </div>
                    <?php
                    if (isset($_GET['action'], $_GET['id'])) {
                        $id = $_GET['id'];
                        switch ($_GET['action']) {
                            case "delete":
                                $result = $categoryManager->deleteCategory($id);
                                if ($result) {
                                    $successM = "categories deleted successfully";
                                } else {
                                    $err = 'categories not deleted ';
                                }
                                break;
                            default:
                                echo "ERROR";
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
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $categories = $categoryManager->getCategories();
                                    if ($categories) {
                                        foreach ($categories as $category) {
                                            $id = $category['category_id'];
                                            $name = $category['name'];
                                            $description = $category['description'];
                                    ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $id ?></td>
                                                <td><?php echo $name ?></td>
                                                <td><?php echo $description ?></td>
                                                <td>
                                                    <a href="editcategory.php?action=edit&id=<?php echo $id ?>"
                                                        class='btn btn-success'>Edit</a>
                                                    <a href="?action=delete&id=<?php echo $id ?>"
                                                        class='delete btn btn-danger'>Delete</a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <div class='alert alert-danger'>No Rows Found</div>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('upload/footer.php');
?>

<script>
    $(document).ready(function() {
        $('.delete').click(function() {
            return confirm('Are You Sure?');
        });
    });
</script>
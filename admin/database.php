<?php
include('upload/header.php');
require('db_connect.php');

$backupDate = $backupName = "";
$errors = array();

if (isset($_POST['submit'])) {

    // Set the database credentials
    // $host = 'localhost';  // Replace with your database host
    // $dbname = 'furniture_store';  // Replace with your database name
    // $username = 'root';  // Replace with your database username
    // $password = '';  // Replace with your database password
    $host = "localhost";
    $dbName = "furniture_store";
    $username = "root";
    $password = "";







    if (empty($_POST["backup_name"])) {
        $errors['backup_nameE'] = "BackupName is required";
    } else {
        $backupName = test_input($_POST["backup_name"]);
        // check if about me only contains letters, whitespace, and numbers
        if (!preg_match("/^[a-zA-Z0-9\s'-]*$/", $backupName)) {
            $errors['backup_nameE'] = "Only letters, numbers, whitespace,  apostrophes, and hyphens allowed in the about me field";
        }
    }



    if (empty($_POST["backup_date"])) {
        $errors['backup_dateE'] = "BackupDate is required";
    } else {
        $backupDate = test_input($_POST["backup_date"]);
        // Additional validation for the birthdate if needed
    }



    if (empty($errors)) {


        // // Set the backup file name and path
        // $backupName = $_POST['backup_name'];  // Assuming you have an input field with name="backup_name" in your form
        // $backupDate = $_POST['backup_date'];  // Assuming you have an input field with name="backup_date" in your form
        $backupFileName = $backupName . '_' . $backupDate . '.sql';  // Example: backup_2023-10-23.sql
        $backupFilePath = '../uploads/backup/' . $backupFileName;  // Replace with the actual path to your backup directory

        // Create the backup command
        $command = "mysqldump --host={$host} --user={$username} --password={$password} {$dbname} > {$backupFilePath}";

        // Execute the backup command
        exec($command, $output, $returnVar);


        // Check if the backup operation was successful
        if ($returnVar === 0) {
            $_SESSION['message'] = "<div class='alert alert-success'>Backup operation was successful </div>";

            // echo "Backup created successfully!";
        } else {

            $_SESSION['message'] = "<div class='alert alert-danger'>Backup creation failed. Error:  . implode('\n', $output)</div>";
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacy-Stocks-Management-System</title>

    <link rel="stylesheet" href="asset/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="asset/css/adminlte.min.css">
    <link rel="stylesheet" href="asset/css/style.css">

</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <!-- Main content -->
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="card-header">
                <h3 class="card-title">Database Information</h3>
                <!-- <?php
                        echo $returnVar . "<br>"; ?> -->
            </div>
            <?php
            if (isset($_SESSION['message'])) {
                echo  $_SESSION['message'] . '</p>';
                unset($_SESSION['message']);
            }

            // echo "<div class='alert alert-danger'>One Row  not Updated </div>";

            ?>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header">
                                <span class="fa fa-user"> Database Information</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Backup Name</label>
                                        <input type="text" name="backup_name" class="form-control" placeholder="Backup name">
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['backup_nameE'])) echo $errors['backup_nameE'];
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Backup Date</label>
                                        <input type="date" name="backup_date" class="form-control">
                                        <span style="color:red">
                                            <?php
                                            if (isset($errors['backup_dateE'])) echo $errors['backup_dateE'];
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Backup</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="asset/js/jquery.min.js"></script>
    <script src="asset/js/bootstrap.bundle.min.js"></script>
    <script src="asset/js/adminlte.min.js"></script>
</body>

</html>
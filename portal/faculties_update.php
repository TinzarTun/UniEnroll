<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/faculties_update.php");

    if (isset($_REQUEST['fid'])) 
    {
        $facultiesID=$_REQUEST['fid'];

        $select=mysqli_query($connection,"SELECT * FROM faculties 
                                        WHERE FacultiesID='$facultiesID'");
        $data=mysqli_fetch_array($select);

        $Faculties_Name=$data['Name'];
        $Founded_Year=$data['Founded_year'];
        $Faculties_Status=$data['Status'];
    }

    if (isset($_POST['btnupdate'])) 
    {
        $FID=$_POST['txtFID'];
        $name=$_POST['txtname'];
        $year=$_POST['txtyear'];
        $status=$_POST['cbostatus'];

        // Validate founded year (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $year)) {
            echo "<script>alert('Founded Year must be a 4-digit year like 1999 or 2026')</script>";
            echo "<script>location='faculties_list.php'</script>";
            exit();
        }

        $update="UPDATE faculties
                SET Name='$name', Founded_year='$year', Status='$status'
                WHERE FacultiesID='$FID'";
        $run=mysqli_query($connection,$update);

        if ($run) 
        {
            echo "<script>alert('Update Successful!')</script>";
            echo "<script>location='faculties_list.php'</script>";
        }

        else
        {
            echo mysqli_error($connection);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.php'); ?>
</head>
<body>
    <?php include('pre-loader.php'); ?>

    <?php include('header.php'); ?>

    <?php include('sidebar.php'); ?>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <!-- Page Header Start -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Faculties</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties_list.php">Faculties List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Faculties Update</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header End -->

                <!-- Faculties Form Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Update Form</h4>
                            <p class="mb-30">Please update faculties information</p>
                        </div>
                        <div class="pull-right">
                            <a href="faculties_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="faculties_update.php" method="post">
                        <input type="hidden" name="txtFID" value="<?php echo $facultiesID ?>">
                        <div class="form-group">
                            <label>Faculties Name</label>
                            <input class="form-control" type="text" name="txtname" value="<?php echo $Faculties_Name ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Founded Year</label>
                            <input class="form-control" type="text" name="txtyear" value="<?php echo $Founded_Year ?>" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2026)" required>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="cbostatus" required/>
                                <option><?php echo $Faculties_Status ?></option>
                                <?php 
                                    if($status!="Active")
                                    {
                                        echo"<option value='Active'>Active</option>";
                                    }

                                    if($status!="Inactive")
                                    {
                                        echo"<option value='Inactive'>Inactive</option>";
                                    }
                                 ?>
                            </select>
                        </div>

                        <div class="clearfix">
                            <div class="pull-left">
                                <p class="mb-30 font-14"></p>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-success" type="submit" name="btnupdate">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Faculties Form End -->
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

     <?php include('script.php'); ?>
</body>
</html>
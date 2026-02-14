<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/faculties.php");

if (isset($_POST['btnregister'])) 
{
    $FID=$_POST['txtFID'];
    $txtname=$_POST['txtname'];
    $name = 'Faculties of ' . $txtname;
    $year=$_POST['txtyear'];
    $status="Active";

    // Validate founded year (YYYY)
    if (!preg_match('/^[0-9]{4}$/', $year)) {
        echo "<script>alert('Founded Year must be a 4-digit year like 1999 or 2026')</script>";
        echo "<script>location='faculties.php'</script>";
        exit();
    }

    $select=mysqli_query($connection,"SELECT * FROM faculties 
                                                    WHERE Name='$name'");
    $count=mysqli_num_rows($select);
    if ($count==0) 
    {
        $insert=mysqli_query($connection,"INSERT INTO faculties(FacultiesID, Name, Founded_year, Status) 
                                                        VALUES('$FID', '$name', '$year','$status')");
        if ($insert) 
        {
            echo "<script>alert('Faculties Register Success!')</script>";
            echo "<script>location='faculties_list.php'</script>";
        }

        else
        {
            echo mysqli_error($connection);
        }
    }

    else
    {
        echo "<script>alert('Faculties Already Exist!')</script>";
        echo "<script>location='faculties.php'</script>";
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
                                    <li class="breadcrumb-item active" aria-current="page">Faculties Register</li>
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
                            <h4 class="text-blue h4">Registration Form</h4>
                            <p class="mb-30">Please fill faculties information</p>
                        </div>
                        <div class="pull-right">
                            <a href="faculties_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="faculties.php" method="post">
                        <input type="hidden" name="txtFID" value="<?php echo AutoID('faculties', 'FacultiesID', 'FID-', 4) ?>">
                        <div class="form-group">
                            <label>Faculties of</label>
                            <input class="form-control" type="text" name="txtname" placeholder="e.g. Science, Engineering, Business" required>
                        </div>

                        <div class="form-group">
                            <label>Founded Year</label>
                            <input class="form-control" type="text" name="txtyear" placeholder="YYYY (e.g. 2026)" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2026)" required>
                        </div>

                        <div class="clearfix">
                            <div class="pull-left">
                                <p class="mb-30 font-14"></p>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-success" type="submit" name="btnregister">Register</button>
                                <button class="btn btn-danger" type="reset" name="btncancel">Cancel</button>
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
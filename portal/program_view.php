<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/program_view.php");

    if (isset($_REQUEST['did'])) 
        {
            $departmentID=$_REQUEST['did'];

            $select=mysqli_query($connection,"SELECT 
                                                                d.DepartmentID,
                                                                d.Name AS department_name,
                                                                f.Name AS faculty_name,
                                                                d.Founded_year AS department_year,
                                                                f.Founded_year AS faculty_year,
                                                                d.Status AS department_status,
                                                                f.Status AS faculty_status
                                                            FROM department d
                                                            JOIN faculties f 
                                                                ON d.FacultiesID = f.FacultiesID
                                                            WHERE d.DepartmentID='$departmentID'");
            $data=mysqli_fetch_array($select);

            $department_name=$data['department_name'];
            $faculty_name=$data['faculty_name'];
            $department_year=$data['department_year'];
            $faculty_year=$data['faculty_year'];
            $department_status=$data['department_status'];
            $faculty_status=$data['faculty_status'];
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
                                <h4>Program</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="program_list.php">Program List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Program Detail</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header End -->

                <!-- Program Form Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">View Form</h4>
                            <p class="mb-30">Detail information of <?php echo $department_name ?> department</p>
                        </div>
                        <div class="pull-right">
                            <a href="program_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="program_view.php" method="post">
                        <input type="hidden" name="txtDID" value="<?php echo $departmentID ?>">
                        <label class="<?php echo ($faculty_status=='Active')?'text-success':'text-danger'; ?> weight-600">
                            Faculties Information
                        </label>

                        <div class="form-group">
                            <label>Faculties Name</label>
                            <input class="form-control" type="text" value="<?php echo $faculty_name ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Founded Year</label>
                            <input class="form-control" type="text" value="<?php echo $faculty_year ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <input class="form-control" type="text" value="<?php echo $faculty_status ?>" readonly>
                        </div>

                        <label class="<?php echo ($department_status=='Active')?'text-success':'text-danger'; ?> weight-600">
                            Department Information
                        </label>

                        <div class="form-group">
                            <label>Department Name</label>
                            <input class="form-control" type="text" value="<?php echo $department_name ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Founded Year</label>
                            <input class="form-control" type="text" value="<?php echo $department_year ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <input class="form-control" type="text" value="<?php echo $department_status ?>" readonly>
                        </div>

                        <div class="clearfix">
                            <div class="pull-left">
                                <p class="mb-30 font-14"></p>
                            </div>
                            <div class="pull-right">
                                <a href="program_list.php" class="btn btn-primary btn-sm scroll-click" role="button">Go Back</a>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Program Form End -->
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

     <?php include('script.php'); ?>
</body>
</html>
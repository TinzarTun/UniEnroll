<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/department.php");

    if (isset($_POST['btnregister'])) 
    {
        $PGID=$_POST['txtPGID'];
        $name=$_POST['txtname'];
        $duration=$_POST['txtduration'];
        $start=$_POST['txtstart'];
        $lvl=$_POST['cbolvl'];
        $department=$_POST['cbodepartment'];
        $status="Active";

        // Validate duration year (1-9)
        if (!preg_match('/^[1-9]{1}$/', $duration)) {
            echo "<script>alert('Duration must be a single digit between 1 and 9 years')</script>";
            echo "<script>location='program.php'</script>";
            exit();
        }

        // Validate start year (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $start)) {
            echo "<script>alert('Start Year must be a 4-digit year like 1999 or 2026')</script>";
            echo "<script>location='program.php'</script>";
            exit();
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
                                <h4>Program</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="program_list.php">Program List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Program Register</li>
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
                            <h4 class="text-blue h4">Registration Form</h4>
                            <p class="mb-30">Please fill program information</p>
                        </div>
                        <div class="pull-right">
                            <a href="program_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="program.php" method="post">
                        <input type="hidden" name="txtPGID" value="<?php echo AutoID('program', 'ProgramID', 'PGID-', 4) ?>">

                        <div class="form-group">
                            <label>Department Name</label>
                            <select class="selectpicker form-control" name="cbodepartment" required>
                                <option value="">Choose Department Name</option>
                                
                                <optgroup label="Active">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM department ORDER BY Name ASC");
                                        $count=mysqli_num_rows($select);
                                        for ($i=0; $i < $count; $i++) 
                                        { 
                                            $data=mysqli_fetch_array($select);
                                            $departmentID=$data['DepartmentID'];
                                            $departmentName=$data['Name'];
                                            $departmentYear=$data['Founded_year'];
                                            $DepartmentStatus=$data['Status'];

                                            if($DepartmentStatus=="Active")
                                            {
                                                echo "<option value='$departmentID'>Department of $departmentName, $departmentYear</option>"; 
                                            }
                                        }
                                     ?>
                                </optgroup>

                                <optgroup label="Inactive">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM department ORDER BY Name ASC");
                                        $count=mysqli_num_rows($select);
                                        for ($i=0; $i < $count; $i++) 
                                        { 
                                            $data=mysqli_fetch_array($select);
                                            $departmentID=$data['DepartmentID'];
                                            $departmentName=$data['Name'];
                                            $departmentYear=$data['Founded_year'];
                                            $departmentStatus=$data['Status'];

                                            if($departmentStatus=="Inactive")
                                            {
                                                echo "<option disabled>Department of $departmentName, $departmentYear</option>";
                                            }
                                        }
                                     ?>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Program Name</label>
                            <input class="form-control" type="text" name="txtname" placeholder="Please enter program name" required>
                        </div>

                        <div class="form-group">
                            <label>Degree Level</label>
                            <select class="selectpicker form-control" name="cbolvl" required>
                                <option value='Diploma'>Diploma</option>
                                <option value='Bachelor'>Bachelor</option>
                                <option value='Master'>Master</option>
                                <option value='PhD'>PhD</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Duration Years</label>
                            <input class="form-control" type="text" name="txtduration" placeholder="e.g. 1,2,3,4" pattern="[1-9]{1}" maxlength="1" required>
                        </div>

                        <div class="form-group">
                            <label>Start Year</label>
                            <input class="form-control" type="text" name="txtstart" placeholder="YYYY (e.g. 2026)" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2026)" required>
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
                <!-- Program Form End -->
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

     <?php include('script.php'); ?>
</body>
</html>
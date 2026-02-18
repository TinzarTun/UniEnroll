<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/department_update.php");

    if (isset($_REQUEST['did'])) 
    {
        $departmentID=$_REQUEST['did'];

        $select=mysqli_query($connection,"SELECT 
                                                            d.DepartmentID,
                                                            d.FacultiesID,
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
        $faculty_ID=$data['FacultiesID'];
    }

    if (isset($_POST['btnupdate'])) 
    {
        $DID=$_POST['txtDID'];
        $name=$_POST['txtname'];
        $year=$_POST['txtyear'];
        $faculties=$_POST['cbofname'];
        $status=$_POST['cbostatus'];

        // Check if department name already exists
        $checkName = mysqli_query($connection, "
            SELECT DepartmentID 
            FROM department 
            WHERE Name='$name' AND DepartmentID != '$DID'
        ");
        if (mysqli_num_rows($checkName) > 0) {
            echo "<script>alert('This department name already exists. Please use a different name.')</script>";
            echo "<script>location='department_list.php'</script>";
            exit();
        }

        // Validate founded year (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $year)) {
            echo "<script>alert('Founded Year must be a 4-digit year like 1999 or 2026')</script>";
            echo "<script>location='department_list.php'</script>";
            exit();
        }

        // Get faculties founded year
        $facQuery = mysqli_query($connection, "
            SELECT Founded_year 
            FROM faculties 
            WHERE FacultiesID = '$faculties'
        ");
        $facData = mysqli_fetch_assoc($facQuery);
        $facultyYear = $facData['Founded_year'];

        // Compare founded years
        if ($year < $facultyYear) {
            echo "<script>alert('Department cannot be founded before its faculty (Faculty founded: $facultyYear)')</script>";
            echo "<script>location='department_list.php'</script>";
            exit();
        }

        // Re-check faculty status from DB
        $checkFaculty = mysqli_query($connection, "
            SELECT Status 
            FROM faculties 
            WHERE FacultiesID = '$faculties'
        ");
        $facRow = mysqli_fetch_assoc($checkFaculty);

        // Re-fetch department status inside POST
        $currentDept = mysqli_query($connection, "
            SELECT Status FROM department WHERE DepartmentID='$DID'
        ");
        $currentDeptRow = mysqli_fetch_assoc($currentDept);

        if ($facRow['Status'] == "Inactive") {
            // Force department status to remain unchanged
            $status = $currentDeptRow['Status'];
        }

        $update="UPDATE department
                SET FacultiesID='$faculties', Name='$name', Founded_year='$year', Status='$status'
                WHERE DepartmentID='$DID'";
        $run=mysqli_query($connection,$update);

        if ($run) 
        {
            echo "<script>alert('Update Successful!')</script>";
            echo "<script>location='department_list.php'</script>";
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
                                <h4>Department</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="department_list.php">Department List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Department Update</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header End -->

                <!-- Department Form Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Update Form</h4>
                            <p class="mb-30">Please update department information</p>
                        </div>
                        <div class="pull-right">
                            <a href="department_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="department_update.php" method="post">
                        <input type="hidden" name="txtDID" value="<?php echo $departmentID ?>">
                        <div class="form-group">
                            <label>Department Name</label>
                            <input class="form-control" type="text" name="txtname" value="<?php echo $department_name ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Faculties Name</label>
                            <select class="selectpicker form-control" name="cbofname" required>
                                <option value="<?php echo $faculty_ID ?>"><?php echo $faculty_name ?>, <?php echo $faculty_year ?>, <?php echo $faculty_status ?></option>
                                <option value="">Choose Faculties Name</option>
                                
                                <optgroup label="Active">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM faculties ORDER BY Name ASC");
                                        $count=mysqli_num_rows($select);
                                        for ($i=0; $i < $count; $i++) 
                                        { 
                                            $data=mysqli_fetch_array($select);
                                            $facultiesID=$data['FacultiesID'];
                                            $facultiesName=$data['Name'];
                                            $facultiesYear=$data['Founded_year'];
                                            $facultiesStatus=$data['Status'];

                                            if($facultiesStatus=="Active")
                                            {
                                                echo "<option value='$facultiesID'>$facultiesName, $facultiesYear</option>"; 
                                            }
                                        }
                                     ?>
                                </optgroup>

                                <optgroup label="Inactive">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM faculties ORDER BY Name ASC");
                                        $count=mysqli_num_rows($select);
                                        for ($i=0; $i < $count; $i++) 
                                        { 
                                            $data=mysqli_fetch_array($select);
                                            $facultiesID=$data['FacultiesID'];
                                            $facultiesName=$data['Name'];
                                            $facultiesYear=$data['Founded_year'];
                                            $facultiesStatus=$data['Status'];

                                            if($facultiesStatus=="Inactive")
                                            {
                                                echo "<option disabled>$facultiesName, $facultiesYear</option>";
                                            }
                                        }
                                     ?>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Founded Year</label>
                            <input class="form-control" type="text" name="txtyear" value="<?php echo $department_year ?>" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2026)" required>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <?php if ($faculty_status == "Inactive") { ?>
                                <!-- Faculty inactive → status locked -->
                                <input type="text" class="form-control" value="<?php echo $department_status ?>" readonly>
                                <input type="hidden" name="cbostatus" value="<?php echo $department_status ?>">
                                <small class="text-danger">
                                    Department status cannot be changed because the faculty is inactive.
                                </small>
                            <?php } else { ?>
                                <!-- Faculty active → editable -->
                                <select class="selectpicker form-control" name="cbostatus" required>
                                    <option><?php echo $department_status ?></option>
                                    <?php 
                                        if($department_status!="Active")
                                        {
                                            echo"<option value='Active'>Active</option>";
                                        }

                                        if($department_status!="Inactive")
                                        {
                                            echo"<option value='Inactive'>Inactive</option>";
                                        }
                                    ?>
                                </select>
                            <?php } ?>
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
                <!-- Department Form End -->
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

     <?php include('script.php'); ?>
</body>
</html>
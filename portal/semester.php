<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/semester.php");

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

        // Get department founded year
        $depQuery = mysqli_query($connection, "
            SELECT Founded_year 
            FROM department 
            WHERE DepartmentID = '$department'
        ");
        $depData = mysqli_fetch_assoc($depQuery);
        $departmentYear = $depData['Founded_year'];

        // Compare years
        if ($start < $departmentYear) {
            echo "<script>alert('Program start year cannot be earlier than its department founded year (Department founded: $departmentYear).')</script>";
            echo "<script>location='program.php'</script>";
            exit();
        }

        $select=mysqli_query($connection,"SELECT * FROM program 
                                                        WHERE Program_Name='$name'");
        $count=mysqli_num_rows($select);
        if ($count==0) 
        {
            $insert=mysqli_query($connection,"INSERT INTO program(ProgramID, DepartmentID, Program_Name, Degree_level, Duration_years, Start_year, Status) 
                                                            VALUES('$PGID', '$department','$name', '$lvl', '$duration', '$start','$status')");
            if ($insert) 
            {
                echo "<script>alert('Program Register Success!')</script>";
                echo "<script>location='program_list.php'</script>";
            }

            else
            {
                echo mysqli_error($connection);
            }
        }

        else
        {
            echo "<script>alert('Program Already Exist!')</script>";
            echo "<script>location='program.php'</script>";
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
                                <h4>Semester</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="semester_list.php">Semester List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Semester Register</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header End -->

                <!-- Semester Form Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Registration Form</h4>
                            <p class="mb-30">Please fill semester information</p>
                        </div>
                        <div class="pull-right">
                            <a href="semester_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="semester.php" method="post">
                        <input type="hidden" name="txtSMID" value="<?php echo AutoID('semester', 'SemesterID', 'SMID-', 4) ?>">

                        <div class="form-group">
                            <label>Program Name</label>
                            <select class="selectpicker form-control" name="cboprogram" required>
                                <option value="">Choose Program Name</option>
                                
                                <optgroup label="Active">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM program ORDER BY Program_Name ASC");
                                        $count=mysqli_num_rows($select);
                                        for ($i=0; $i < $count; $i++) 
                                        { 
                                            $data=mysqli_fetch_array($select);
                                            $programID=$data['ProgramID'];
                                            $programName=$data['Program_Name'];
                                            $degreeLevel=$data['Degree_level'];
                                            $durationYears=$data['Duration_years'];
                                            $programStatus=$data['Status'];

                                            $yearText = ($durationYears > 1) ? 'years' : 'year';

                                            if($programStatus=="Active")
                                            {
                                                echo "<option value='$programID'>$programName, $degreeLevel, $durationYears $yearText</option>"; 
                                            }
                                        }
                                     ?>
                                </optgroup>

                                <optgroup label="Inactive">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM program ORDER BY Program_Name ASC");
                                        $count=mysqli_num_rows($select);
                                        for ($i=0; $i < $count; $i++) 
                                        { 
                                            $data=mysqli_fetch_array($select);
                                            $programID=$data['ProgramID'];
                                            $programName=$data['Program_Name'];
                                            $degreeLevel=$data['Degree_level'];
                                            $durationYears=$data['Duration_years'];
                                            $programStatus=$data['Status'];

                                            $yearText = ($durationYears > 1) ? 'years' : 'year';

                                            if($programStatus=="Inactive")
                                            {
                                                echo "<option disabled>$programName, $degreeLevel, $durationYears $yearText</option>";
                                            }
                                        }
                                     ?>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Academic Year</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >From :</label>
                                        <input class="form-control" type="text" name="txtAfrom" placeholder="YYYY (e.g. 2025)" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2025)" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >To :</label>
                                        <input class="form-control" type="text" name="txtAto" placeholder="YYYY (e.g. 2026)" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2026)" required>
                                    </div>
                                </div>
                            </div>
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
                <!-- Semester Form End -->
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

     <?php include('script.php'); ?>
</body>
</html>
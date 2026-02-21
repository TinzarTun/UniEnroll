<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/semester_update.php");

    if (isset($_POST['btnregister'])) 
    {
        $SMID=$_POST['txtSMID'];
        $program=$_POST['cboprogram'];
        $type=$_POST['cbotype'];
        $year=$_POST['txtyear'];
        $from=$_POST['txtfrom'];
        $to=$_POST['txtto'];
        $start=date('Y-m-d',strtotime($_POST['txtstart']));
        $end=date('Y-m-d',strtotime($_POST['txtend']));
        $status="Planned";

        if ($start >= $end) {
            echo "<script>alert('The semester end date must be later than the start date. Please correct the dates.')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Validate intake year (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $year)) {
            echo "<script>alert('Please enter a valid 4-digit year for the intake (e.g. 2026).')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Validate academic year from (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $from)) {
            echo "<script>alert('Please enter a valid 4-digit year for the academic start year (e.g. 2026).')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Validate academic year to (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $to)) {
            echo "<script>alert('Please enter a valid 4-digit year for the academic end year (e.g. 2026).')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // To prevent wrong order
        if ($from >= $to) {
            echo "<script>alert('The academic end year must be later than the start year. Please correct it.')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Get program start year
        $proQuery = mysqli_query($connection, "
            SELECT Start_year, Duration_years
            FROM program 
            WHERE ProgramID = '$program'
        ");
        $ProData = mysqli_fetch_assoc($proQuery);
        if (!$ProData) {
            echo "<script>alert('Invalid Program Selected')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }
        $ProgramYear = $ProData['Start_year'];

        // Compare years
        if ($year < $ProgramYear) {
            echo "<script>alert('The intake year cannot be earlier than the program start year ($ProgramYear). Please choose a valid year.')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        if ($from < $ProgramYear) {
            echo "<script>alert('The academic start year cannot be earlier than the program start year ($ProgramYear).')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        if ($to < $ProgramYear) {
            echo "<script>alert('The academic end year cannot be earlier than the program start year ($ProgramYear).')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Build intake name & academic year
        $intakeName= $type.' '.$year; // Fall 2026
        $academicYear= $from.'-'.$to; //2025-2026

        // Get next semester number for this program + intake
        $query = mysqli_query($connection,
            "SELECT IFNULL(MAX(Semester),0)+1 AS next_sem
            FROM semester
            WHERE ProgramID='$program' 
            AND Intake_name='$intakeName'");
        $row = mysqli_fetch_assoc($query);
        $semester_no = $row['next_sem'];
        $semester_name = "Semester $semester_no";

        // Check program duration limit
        $ProgramDurationYears = $ProData['Duration_years']; // e.g., 4 years
        $maxSemesters = $ProgramDurationYears * 2; // 2 semesters per year

        if ($semester_no > $maxSemesters) {
            echo "<script>alert('You cannot add Semester $semester_no because this program only allows up to $maxSemesters semesters.')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Check if semester already exists
        $checkDuplicate = mysqli_query($connection, "
            SELECT 1 
            FROM semester 
            WHERE ProgramID = '$program'
              AND Intake_name = '$intakeName'
              AND Semester = '$semester_no'
            LIMIT 1
        ");

        if (mysqli_num_rows($checkDuplicate) > 0) 
        {
            echo "<script>alert('This semester already exists for the selected program and intake.')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        else 
        {
            $insert=mysqli_query($connection,"INSERT INTO semester
                                                            (SemesterID, ProgramID, Intake_type, Intake_name, Semester, Semester_name, Academic_year, Start_date, End_date, Status)
                                                            VALUES
                                                            ('$SMID','$program','$type','$intakeName',$semester_no,'$semester_name','$academicYear','$start','$end','$status')"
                                                            );
            if ($insert) 
            {
                echo "<script>alert('Semester Register Success!')</script>";
                echo "<script>location='semester_list.php'</script>";
            }

            else
            {
                echo mysqli_error($connection);
            }
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
                                    <li class="breadcrumb-item active" aria-current="page">Semester Update</li>
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
                            <h4 class="text-blue h4">Update Form</h4>
                            <p class="mb-30">Please fill update information</p>
                        </div>
                        <div class="pull-right">
                            <a href="semester_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="semester_update.php" method="post">
                        <input type="hidden" name="txtSMID" value="<?php echo AutoID('semester', 'SemesterID', 'SMID-', 4) ?>">

                        <div class="form-group">
                            <label>Program Name</label>
                            <select class="selectpicker form-control" name="cboprogram" required>
                                <option value="">Choose Program Name</option>
                                
                                <optgroup label="Active">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM program 
                                                                                        ORDER BY 
                                                                                        CASE Degree_level
                                                                                            WHEN 'Diploma' THEN 1
                                                                                            WHEN 'Bachelor' THEN 2
                                                                                            WHEN 'Master' THEN 3
                                                                                            WHEN 'PhD' THEN 4
                                                                                        END,
                                                                                        Program_Name ASC");
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
                                                echo "<option value='$programID'>$programName, $durationYears $yearText $degreeLevel</option>"; 
                                            }
                                        }
                                     ?>
                                </optgroup>

                                <optgroup label="Inactive">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM program 
                                                                                        ORDER BY 
                                                                                        CASE Degree_level
                                                                                            WHEN 'Diploma' THEN 1
                                                                                            WHEN 'Bachelor' THEN 2
                                                                                            WHEN 'Master' THEN 3
                                                                                            WHEN 'PhD' THEN 4
                                                                                        END,
                                                                                        Program_Name ASC");
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
                                                echo "<option disabled>$programName, $durationYears $yearText $degreeLevel</option>";
                                            }
                                        }
                                     ?>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Intake Name</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Type :</label>
                                        <select class="selectpicker form-control" name="cbotype" required>
                                            <option value='Fall'>Fall</option>
                                            <option value='Spring'>Spring</option>
                                            <option value='Summer'>Summer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Year :</label>
                                        <input class="form-control" type="text" name="txtyear" placeholder="YYYY (e.g. 2026)" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2026)" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Academic Year</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >From :</label>
                                        <input class="form-control" type="text" name="txtfrom" placeholder="YYYY (e.g. 2025)" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2025)" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >To :</label>
                                        <input class="form-control" type="text" name="txtto" placeholder="YYYY (e.g. 2026)" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2026)" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Semester Start & End Date</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Start Date :</label>
                                        <input class="form-control date-picker" type="text" name="txtstart" placeholder="Select Start Date" value="<?php echo date('Y-m-d') ?>" onClick="showCalender(calender,this)" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >End Date :</label>
                                        <input class="form-control date-picker" type="text" name="txtend" placeholder="Select End Date" value="<?php echo date('Y-m-d') ?>" onClick="showCalender(calender,this)" required>
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
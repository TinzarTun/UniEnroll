<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/semester.php");

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

        // Validate intake year (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $year)) {
            echo "<script>alert('Intake Year must be a 4-digit year like 1999 or 2026')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Validate academic year from (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $from)) {
            echo "<script>alert('Academic (From) Year must be a 4-digit year like 1999 or 2026')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Validate academic year to (YYYY)
        if (!preg_match('/^[0-9]{4}$/', $to)) {
            echo "<script>alert('Academic (To) Year must be a 4-digit year like 1999 or 2026')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Get program start year
        $proQuery = mysqli_query($connection, "
            SELECT Start_year 
            FROM program 
            WHERE ProgramID = '$program'
        ");
        $ProData = mysqli_fetch_assoc($proQuery);
        $ProgramYear = $ProData['Start_year'];
    }

    // Compare years
    if ($year < $ProgramYear) {
        echo "<script>alert('Semester intake year cannot be earlier than its program start year (Program started: $ProgramYear).')</script>";
        echo "<script>location='semester.php'</script>";
        exit();
    }

    if ($from < $ProgramYear) {
        echo "<script>alert('Semester academic (From) year cannot be earlier than its program start year (Program started: $ProgramYear).')</script>";
        echo "<script>location='semester.php'</script>";
        exit();
    }

    if ($to < $ProgramYear) {
        echo "<script>alert('Semester academic (To) year cannot be earlier than its program start year (Program started: $ProgramYear).')</script>";
        echo "<script>location='semester.php'</script>";
        exit();
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
                            <label class="font-weight-bold">Start Date & End Date</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Start :</label>
                                        <input class="form-control date-picker" type="text" name="txtstart" placeholder="Select Start Date" value="<?php echo date('Y-m-d') ?>" OnClick="showCalender(calender,this)" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >End :</label>
                                        <input class="form-control date-picker" type="text" name="txtend" placeholder="Select End Date" value="<?php echo date('Y-m-d') ?>" OnClick="showCalender(calender,this)" required>
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
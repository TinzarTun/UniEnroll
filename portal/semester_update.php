<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/semester_update.php");

    if (isset($_REQUEST['smid'])) 
    {
        $semesterID=$_REQUEST['smid'];

        $select=mysqli_query($connection,"SELECT 
                                                            s.SemesterID,
                                                            s.Intake_name,
                                                            s.Semester_name,
                                                            s.Academic_year,
                                                            s.Start_date,
                                                            s.End_date,
                                                            s.Status AS semester_status,

                                                            p.ProgramID,
                                                            p.Program_Name,
                                                            p.Degree_level,
                                                            p.Duration_years,
                                                            p.Status AS program_status
                                                        FROM semester s
                                                        JOIN program p 
                                                            ON s.ProgramID = p.ProgramID

                                                        WHERE s.SemesterID = '$semesterID'");
        $data=mysqli_fetch_array($select);

        // Semester
        $intake_name       = $data['Intake_name'];
        $semester_name     = $data['Semester_name'];
        $academic_year     = $data['Academic_year'];
        $start_date        = $data['Start_date'];
        $end_date          = $data['End_date'];
        $semester_status   = $data['semester_status'];

        // Program
        $program_name     = $data['Program_Name'];
        $degree_level     = $data['Degree_level'];
        $duration_years   = $data['Duration_years'];
        $program_status   = $data['program_status'];
        $yearText         = ($duration_years > 1) ? 'years' : 'year';

        // Lock logic
        $isCompleted = ($semester_status === "Completed");
        $isProgramInactive = ($program_status === "Inactive");
        $isLockedSemester = ($isCompleted || $isProgramInactive);
    }

    if (isset($_POST['btnupdate'])) 
    {
        $SMID=$_POST['txtSMID'];
        $start=date('Y-m-d',strtotime($_POST['txtstart']));
        $end=date('Y-m-d',strtotime($_POST['txtend']));
        $newStatus=$_POST['cbostatus'];

        if ($start >= $end) {
            echo "<script>alert('The semester end date must be later than the start date. Please correct the dates.')</script>";
            echo "<script>location='semester.php'</script>";
            exit();
        }

        // Get current semester and program status
        $check = mysqli_query($connection, "
            SELECT s.Status AS semester_status, p.Status AS program_status
            FROM semester s
            JOIN program p ON s.ProgramID = p.ProgramID
            WHERE s.SemesterID='$SMID'
        ");
        $row = mysqli_fetch_assoc($check);

        $currentSemesterStatus = $row['semester_status'];
        $programStatus = $row['program_status'];

        // Block edit ONLY if semester is completed
        if ($currentSemesterStatus == "Completed") {
            echo "<script>alert('Completed semesters cannot be edited')</script>";
            echo "<script>location='semester_list.php'</script>";
            exit();
        }

        // Block edit if program inactive
        if ($programStatus == "Inactive") {
            echo "<script>alert('Cannot edit semester because program is inactive')</script>";
            echo "<script>location='semester_list.php'</script>";
            exit();
        }

        $update="UPDATE semester
                SET Start_date='$start', End_date='$end', Status='$newStatus'
                WHERE SemesterID='$SMID'";
        $run=mysqli_query($connection,$update);

        if ($run) 
        {
            echo "<script>alert('Update Successful!')</script>";
            echo "<script>location='semester_list.php'</script>";
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
                        <input type="hidden" name="txtSMID" value="<?php echo $semesterID ?>">

                        <div class="form-group">
                            <label>Program Name</label>
                            <input class="form-control" type="text" value="<?php echo $program_name ?>, <?php echo $duration_years ?> <?php echo $yearText ?> <?php echo $degree_level ?>, <?php echo $program_status ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Intake Name</label>
                            <input class="form-control" type="text" value="<?php echo $intake_name ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Semester Name</label>
                            <input class="form-control" type="text" value="<?php echo $semester_name ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Academic Year</label>
                            <input class="form-control" type="text" value="<?php echo $academic_year ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Semester Start & End Date</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Start Date :</label>
                                        <input 
                                            class="form-control date-picker" 
                                            type="text" 
                                            name="txtstart" 
                                            value="<?php echo $start_date ?>" 
                                            <?php if ($isLockedSemester) { ?>
                                                readonly
                                            <?php } else { ?>
                                                onClick="showCalender(calender,this)"
                                            <?php } ?>
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >End Date :</label>
                                        <input 
                                            class="form-control date-picker" 
                                            type="text" 
                                            name="txtend" 
                                            value="<?php echo $end_date ?>" 
                                            <?php if ($isLockedSemester) { ?>
                                                readonly
                                            <?php } else { ?>
                                                onClick="showCalender(calender,this)"
                                            <?php } ?>
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <?php if ($isLockedSemester) { ?>
                                <input type="text" class="form-control" value="<?php echo $semester_status ?>" readonly>
                                <input type="hidden" name="cbostatus" value="<?php echo $semester_status ?>">

                                <small class="text-danger">
                                    <?php if ($isCompleted) { ?>
                                        This semester is <strong>Completed</strong> and cannot be updated.
                                    <?php } else { ?>
                                        This semester cannot be updated because the program is inactive.
                                    <?php } ?>
                                </small>

                            <?php } else { ?>
                                <!-- Editable -->
                                <select class="selectpicker form-control" name="cbostatus" required>
                                    <option value="<?php echo $semester_status ?>">
                                        <?php echo $semester_status ?>
                                    </option>

                                    <?php if($semester_status!="Planned")   echo "<option value='Planned'>Planned</option>"; ?>
                                    <?php if($semester_status!="Ongoing")   echo "<option value='Ongoing'>Ongoing</option>"; ?>
                                    <?php if($semester_status!="Completed") echo "<option value='Completed'>Completed</option>"; ?>
                                    <?php if($semester_status!="Cancelled") echo "<option value='Cancelled'>Cancelled</option>"; ?>
                                </select>
                            <?php } ?>
                        </div>

                        <div class="clearfix">
                            <div class="pull-left">
                                <p class="mb-30 font-14"></p>
                            </div>
                            <div class="pull-right">
                                <?php if ($isLockedSemester) { ?>
                                    <a href="semester_list.php" class="btn btn-primary btn-sm scroll-click" role="button">
                                        Go Back
                                    </a>
                                <?php } else { ?>
                                    <button class="btn btn-success" type="submit" name="btnupdate">
                                        Update
                                    </button>
                                <?php } ?>
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
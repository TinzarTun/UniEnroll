<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/semester_view.php");

    if (isset($_REQUEST['smid'])) 
        {
            $semesterID=$_REQUEST['smid'];

            $select=mysqli_query($connection,"SELECT 
                                                                s.SemesterID,
                                                                s.Intake_type,
                                                                s.Intake_name,
                                                                s.Semester,
                                                                s.Semester_name,
                                                                s.Academic_year,
                                                                s.Start_date,
                                                                s.End_date,
                                                                s.Status AS semester_status,

                                                                p.ProgramID,
                                                                p.Program_Name,
                                                                p.Degree_level,
                                                                p.Duration_years,
                                                                p.Start_year,
                                                                p.Status AS program_status,

                                                                d.DepartmentID,
                                                                d.Name AS department_name,
                                                                d.Founded_year AS department_year,
                                                                d.Status AS department_status,

                                                                f.FacultiesID,
                                                                f.Name AS faculty_name,
                                                                f.Founded_year AS faculty_year,
                                                                f.Status AS faculty_status
                                                            FROM semester s
                                                            JOIN program p 
                                                                ON s.ProgramID = p.ProgramID
                                                            JOIN department d 
                                                                ON p.DepartmentID = d.DepartmentID
                                                            JOIN faculties f 
                                                                ON d.FacultiesID = f.FacultiesID

                                                            WHERE s.SemesterID = '$semesterID'");
            $data=mysqli_fetch_array($select);

            // Semester
            $intake_type       = $data['Intake_type'];
            $intake_name       = $data['Intake_name'];
            $semester          = $data['Semester'];
            $semester_name     = $data['Semester_name'];
            $academic_year     = $data['Academic_year'];
            $start_date        = $data['Start_date'];
            $end_date          = $data['End_date'];
            $semester_status   = $data['semester_status'];

            // Program
            $program_name     = $data['Program_Name'];
            $degree_level     = $data['Degree_level'];
            $duration_years   = $data['Duration_years'];
            $start_year       = $data['Start_year'];
            $program_status   = $data['program_status'];
            $yearText         = ($duration_years > 1) ? 'years' : 'year';

            // Department
            $department_name  = $data['department_name'];
            $department_year  = $data['department_year'];
            $department_status= $data['department_status'];

            // Faculty
            $faculty_name     = $data['faculty_name'];
            $faculty_year     = $data['faculty_year'];
            $faculty_status   = $data['faculty_status'];
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
                                    <li class="breadcrumb-item active" aria-current="page">Semester Detail</li>
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
                            <h4 class="text-blue h4">View Form</h4>
                            <p class="mb-30">Detail information of <?php echo $program_name ?> (<?php echo $semester_name ?>, <?php echo $academic_year ?>, <?php echo $intake_name ?> intake)</p>
                        </div>
                        <div class="pull-right">
                            <a href="semester_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="semester_view.php" method="post">
                        <input type="hidden" name="txtSMID" value="<?php echo $semesterID ?>">

                        <!-- Semester Information -->
                        <label class="<?php if ($semester_status=='Planned')   echo 'text-primary';
                                            elseif ($semester_status=='Ongoing') echo 'text-success';
                                            elseif ($semester_status=='Completed') echo 'text-info';
                                            elseif ($semester_status=='Cancelled') echo 'text-danger';?> weight-600">
                            Semester Information
                        </label>

                        <div class="form-group">
                            <label>Intake Type</label>
                            <input class="form-control" type="text" value="<?php echo $intake_type ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Intake Name</label>
                            <input class="form-control" type="text" value="<?php echo $intake_name ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Semester Number</label>
                            <input class="form-control" type="text" value="<?php echo $semester ?>" readonly>
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
                            <label>Semester Start Date</label>
                            <input class="form-control" type="text" value="<?php echo $start_date ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Semester End Date</label>
                            <input class="form-control" type="text" value="<?php echo $end_date ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <input class="form-control" type="text" value="<?php echo $semester_status ?>" readonly>
                        </div>

                        <!-- Program Information -->
                        <label class="<?php echo ($program_status=='Active')?'text-success':'text-danger'; ?> weight-600">
                            Program Information
                        </label>

                        <div class="form-group">
                            <label>Program Name</label>
                            <input class="form-control" type="text" value="<?php echo $program_name ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Degree Level</label>
                            <input class="form-control" type="text" value="<?php echo $degree_level ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Duration</label>
                            <input class="form-control" type="text" value="<?php echo $duration_years ?> <?php echo $yearText ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Start Year</label>
                            <input class="form-control" type="text" value="<?php echo $start_year ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <input class="form-control" type="text" value="<?php echo $program_status ?>" readonly>
                        </div>

                        <!-- Department Information -->
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

                        <!-- Faculties Information -->
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

                        <div class="clearfix">
                            <div class="pull-left">
                                <p class="mb-30 font-14"></p>
                            </div>
                            <div class="pull-right">
                                <a href="semester_list.php" class="btn btn-primary btn-sm scroll-click" role="button">Go Back</a>
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
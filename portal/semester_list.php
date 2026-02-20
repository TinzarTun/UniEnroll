<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/semester_list.php");
?>

<!DOCTYPE html>
<html>
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
                                    <li class="breadcrumb-item active" aria-current="page">Semester List</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header End -->

                <!-- Semester List Table Start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h4 class="text-blue h4">Semester List</h4>
                                <p class="mb-0">List of semester information</p>
                            </div>
                            <div class="pull-right">
                                <a href="semester.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">#</th>
                                    <th>Program Name</th>
                                    <th>Intake</th>
                                    <th>Semester</th>
                                    <th>Academic Year</th>
                                    <th>Status</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $select=mysqli_query($connection,"SELECT 
                                                                                        s.SemesterID,
                                                                                        s.Intake_name,
                                                                                        s.Semester_name,
                                                                                        s.Academic_year,
                                                                                        s.Status AS semester_status,
                                                                                        p.Program_Name,
                                                                                        p.Status AS program_status
                                                                                    FROM semester s
                                                                                    JOIN program p
                                                                                        ON s.ProgramID = p.ProgramID");
                                    $count=mysqli_num_rows($select);
                                    for ($i = 0; $i < $count; $i++) 
                                    {
                                        $data = mysqli_fetch_array($select);
                                        $smid = $data['SemesterID'];
                                        $pname = $data['Program_Name'];
                                        $iname = $data['Intake_name'];
                                        $sname = $data['Semester_name'];
                                        $ayear = $data['Academic_year'];
                                        $status = $data['semester_status'];

                                        echo "<tr>";
                                            echo "<td class='table-plus'>$smid</td>";
                                            echo "<td>$pname</td>";
                                            echo "<td>$iname</td>";
                                            echo "<td>$sname</td>";
                                            echo "<td>$ayear</td>";
                                            echo "<td>$status</td>";
                                                
                                            // if($status=="Active")
                                            // {
                                            //     echo "<td><span class='badge badge-success'>Active</span></td>";
                                            // }

                                            // if($status=="Inactive")
                                            // {
                                            //     echo "<td><span class='badge badge-secondary'>Inactive</span></td>";
                                            // }

                                            echo "<td>
                                                <div class='dropdown'>
                                                    <a class='btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
                                                        <i class='dw dw-more'></i>
                                                    </a>

                                                    <div class='dropdown-menu dropdown-menu-right dropdown-menu-icon-list'>
                                                        <a class='dropdown-item' href='program_view.php?pgid=$pgid'><i class='dw dw-eye'></i> View</a>
                                                        <a class='dropdown-item' href='program_update.php?pgid=$pgid'><i class='dw dw-edit2'></i> Edit</a>
                                                        <a class='dropdown-item' href='program_delete.php?pgid=$pgid'><i class='dw dw-delete-3'></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>";
                                        echo "</tr>";
                                    }
                                 ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Semester List Table End -->
            </div>
            
            <?php include('footer.php'); ?>
        </div>
    </div>
    <?php include('script.php'); ?>
    
    <!-- buttons for Export datatable -->
    <script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.print.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
    <script src="src/plugins/datatables/js/pdfmake.min.js"></script>
    <script src="src/plugins/datatables/js/vfs_fonts.js"></script>
    <!-- Datatable Setting js -->
    <script src="vendors/scripts/datatable-setting.js"></script></body>
</html>
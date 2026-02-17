<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/department_list.php");
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
                                <h4>Department</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Department List</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header End -->

                <!-- Department List Table Start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h4 class="text-blue h4">Department List</h4>
                                <p class="mb-0">List of department information</p>
                            </div>
                            <div class="pull-right">
                                <a href="department.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">#</th>
                                    <th>Department Name</th>
                                    <th>Faculties Name</th>
                                    <th>Status</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $select=mysqli_query($connection,"SELECT 
                                                                                        d.DepartmentID,
                                                                                        d.Name AS department_name,
                                                                                        f.Name AS faculty_name,
                                                                                        d.Status
                                                                                    FROM department d
                                                                                    JOIN faculties f 
                                                                                        ON d.FacultiesID = f.FacultiesID");
                                    $count=mysqli_num_rows($select);
                                    for ($i = 0; $i < $count; $i++) 
                                    {
                                        $data = mysqli_fetch_array($select);
                                        $did = $data['DepartmentID'];
                                        $dname = $data['department_name'];
                                        $fname = $data['faculty_name'];
                                        $status = $data['Status'];

                                        echo "<tr>";
                                            echo "<td class='table-plus'>$did</td>";
                                            echo "<td>$dname</td>";
                                            echo "<td>$fname</td>";
                                                
                                            if($status=="Active")
                                            {
                                                echo "<td><span class='badge badge-success'>Active</span></td>";
                                            }

                                            if($status=="Inactive")
                                            {
                                                echo "<td><span class='badge badge-secondary'>Inactive</span></td>";
                                            }

                                            echo "<td>
                                                <div class='dropdown'>
                                                    <a class='btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
                                                        <i class='dw dw-more'></i>
                                                    </a>

                                                    <div class='dropdown-menu dropdown-menu-right dropdown-menu-icon-list'>
                                                        <a class='dropdown-item' href='department_view.php?did=$did'><i class='dw dw-eye'></i> View</a>
                                                        <a class='dropdown-item' href='department_update.php?did=$did'><i class='dw dw-edit2'></i> Edit</a>
                                                        <a class='dropdown-item' href='department_delete.php?did=$did'><i class='dw dw-delete-3'></i> Delete</a>
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
                <!-- Department List Table End -->
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
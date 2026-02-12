<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/admin/role_list.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.php'); ?>

    <script type="text/javascript" src="../includes/entryjs/jquery-3.1.1.slim.min.js"></script>
    <script type="text/javascript" src="../includes/DataTables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../includes/DataTables/datatables.min.css"/>
</head>
<body>
    <?php include('pre-loader.php'); ?>

    <?php include('header.php'); ?>

    <?php include('sidebar.php'); ?>
    
    <script>
        $(document).ready( function () { $('#tableid').DataTable(); } );
    </script>

    <p>List of Role Information</p>
    <table id="tableid">
        <thead>
            <tr>
                <th>#</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                $select=mysqli_query($connection,"SELECT * FROM role");
                $count=mysqli_num_rows($select);
                for ($i=0; $i < $count; $i++) 
                { 
                    $data=mysqli_fetch_array($select);
                    $rid=$data['RoleID'];
                    $role=$data['Role'];
                    $status=$data['Status'];

                    echo "<tr>";
                        echo "<td>$rid</td>";
                        echo "<td>$role</td>";
											
                        if($status=="Active")
                        {
                            echo "<td>Active</td>";
                        }

                        if($status=="Inactive")
                        {
                            echo "<td>Inactive</td>";
                        }

                        echo "<td>
                                <a href='role_update.php?rid=$rid'>Edit</a>
                                <a href='role_delete.php?rid=$rid'>Delete</a>
                              </td>";
                    echo "</tr>";
                }
             ?>
        </tbody>
    </table>
</body>
</html>
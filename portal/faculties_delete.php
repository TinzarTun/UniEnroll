<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/faculties_delete.php");

    if (isset($_REQUEST['fid'])) 
    {
        $facultiesID=$_REQUEST['fid'];

        // Check if there are linked departments
        $check = mysqli_query($connection, "
            SELECT COUNT(*) AS dept_count 
            FROM department 
            WHERE FacultiesID='$facultiesID'
        ");
        $row = mysqli_fetch_assoc($check);

        if ($row['dept_count'] > 0) {
            echo "<script>alert('Cannot delete faculties: There are departments linked to it. Please reassign or delete the departments first.')</script>";
            echo "<script>location='faculties_list.php'</script>";
            exit();
        }

        // If no linked departments, safe to delete
        $delete="DELETE FROM faculties
                WHERE FacultiesID='$facultiesID'";
        $run=mysqli_query($connection,$delete);
            
        if ($run) 
        {
            echo "<script>alert('Delete Successful!')</script>";
            echo "<script>location='faculties_list.php'</script>";
        }
    }
?>
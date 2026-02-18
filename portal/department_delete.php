<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/department_delete.php");

    if (isset($_REQUEST['did'])) 
    {
        $departmentID=$_REQUEST['did'];

        // Check if programs exist under this department
        $check = mysqli_query($connection, "
            SELECT COUNT(*) AS program_count 
            FROM program 
            WHERE DepartmentID='$departmentID'
        ");
        $row = mysqli_fetch_assoc($check);

        if ($row['program_count'] > 0) {
            echo "<script>alert('Cannot delete department: Programs are linked to this department. Please delete or reassign programs first.')</script>";
            echo "<script>location='department_list.php'</script>";
            exit();
        }

        // If no linked, safe to delete
        $delete="DELETE FROM department
                WHERE DepartmentID='$departmentID'";
        $run=mysqli_query($connection,$delete);
            
        if ($run) 
        {
            echo "<script>alert('Delete Successful!')</script>";
            echo "<script>location='department_list.php'</script>";
        }
    }
?>
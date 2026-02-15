<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/department_delete.php");

    if (isset($_REQUEST['did'])) 
    {
        $departmentID=$_REQUEST['did'];

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
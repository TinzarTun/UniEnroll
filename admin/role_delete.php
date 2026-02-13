<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/admin/role_delete.php");

    if (isset($_REQUEST['rid'])) 
    {
        $roleID=$_REQUEST['rid'];
        $delete="DELETE FROM role
                WHERE RoleID='$roleID'";
        $run=mysqli_query($connection,$delete);
            
        if ($run) 
        {
            echo "<script>alert('Delete Successful!')</script>";
            echo "<script>location='role_list.php'</script>";
        }
    }
?>
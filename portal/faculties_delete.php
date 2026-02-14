<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/faculties_delete.php");

    if (isset($_REQUEST['fid'])) 
    {
        $facultiesID=$_REQUEST['fid'];

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
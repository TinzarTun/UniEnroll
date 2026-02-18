<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/program_delete.php");

    if (isset($_REQUEST['pgid'])) 
    {
        $programID=$_REQUEST['pgid'];

        $delete="DELETE FROM program
                WHERE ProgramID='$programID'";
        $run=mysqli_query($connection,$delete);
            
        if ($run) 
        {
            echo "<script>alert('Delete Successful!')</script>";
            echo "<script>location='program_list.php'</script>";
        }
    }
?>
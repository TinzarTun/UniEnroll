<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/program_delete.php");

    if (isset($_REQUEST['pgid'])) 
    {
        $programID=$_REQUEST['pgid'];

        // Check if semesters exist under this program
        $check = mysqli_query($connection, "
            SELECT COUNT(*) AS semester_count
            FROM semester
            WHERE ProgramID = '$programID'
        ");
        $row = mysqli_fetch_assoc($check);
    
        if ($row['semester_count'] > 0) {
            echo "<script>alert('Cannot delete program: Semesters are linked to this program. Please delete or complete semesters first.')</script>";
            echo "<script>location='program_list.php'</script>";
            exit();
        }

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
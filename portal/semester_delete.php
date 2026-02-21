<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/semester_delete.php");

if (isset($_REQUEST['smid'])) 
{
    $semesterID=$_REQUEST['smid'];

    $check = mysqli_query($connection, "
        SELECT Status
        FROM semester
        WHERE SemesterID='$semesterID'
    ");
    $row = mysqli_fetch_assoc($check);

    if (!$row) {
        echo "<script>alert('Semester not found!')</script>";
        echo "<script>location='semester_list.php'</script>";
        exit();
    }

    if ($row['Status'] == 'Ongoing' || $row['Status'] == 'Completed') {
        echo "<script>alert('Ongoing or Completed semesters cannot be deleted')</script>";
        echo "<script>location='semester_list.php'</script>";
        exit();
    }

    // safe delete
    $delete="DELETE FROM semester
             WHERE SemesterID='$semesterID'";
    $run=mysqli_query($connection,$delete);
            
    if ($run) 
    {
        echo "<script>alert('Delete Successful!')</script>";
        echo "<script>location='semester_list.php'</script>";
    }
}
?>
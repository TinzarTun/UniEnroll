<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/admin/role.php");

if (isset($_POST['btnregister'])) 
{
    $RID=$_POST['txtRID'];
    $role=$_POST['txtrole'];
    $status="Active";

    $select=mysqli_query($connection,"SELECT * FROM role 
                                                    WHERE Role='$role'");
    $count=mysqli_num_rows($select);
    if ($count==0) 
    {
        $insert=mysqli_query($connection,"INSERT INTO role(RoleID, Role, Status) 
                                                        VALUES('$RID', '$role', '$status')");
        if ($insert) 
        {
            echo "<script>alert('Role Register Success!')</script>";
            echo "<script>location='role_list.php'</script>";
        }

        else
        {
            echo mysqli_error($connection);
        }
    }

    else
    {
        echo "<script>alert('Role Already Exist!')</script>";
        echo "<script>location='role.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.php'); ?>
</head>
<body>
    <?php include('pre-loader.php'); ?>

    <p>Please Fill Role Information</p>
    <form action="role.php" method="post">
        <input type="hidden" name="txtRID" value="<?php echo AutoID('role', 'RoleID', 'RID-', 4) ?>">
        <label for="text">Role</label>
        <input type="text" name="txtrole" placeholder="Enter Role Name" required>
        <button type="submit" name="btnregister">Register</button>
        <button type="reset" name="btncancel">Cancel</button>
    </form>

     <?php include('script.php'); ?>
</body>
</html>
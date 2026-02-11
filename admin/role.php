<?php 
    session_start();
    include("../includes/connect.php");

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
        $insert=mysqli_query($connection,"INSERT INTO role(RoleID, Role, RoleStatus) 
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role</title>
</head>
<body>
    <p>Please Fill Role Information</p>
    <form action="role.php" method="post">
        <input type="hidden" name="txtRID" value="">
        <label for="text">Role</label>
        <input type="text" name="txtrole" placeholder="Enter Role Name" required>
        <button type="submit" name="btnregister">Register</button>
        <button type="reset" name="btncancel">Cancel</button>
    </form>
</body>
</html>
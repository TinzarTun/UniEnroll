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

    <?php include('header.php'); ?>

    <?php include('sidebar.php'); ?>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Role</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="role_list.php">Role List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Role Register</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Role Form Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Registration Form</h4>
                            <p class="mb-30">Please fill role information</p>
                        </div>
                        <div class="pull-right">
                            <a href="role_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="role.php" method="post">
                        <input type="hidden" name="txtRID" value="<?php echo AutoID('role', 'RoleID', 'RID-', 4) ?>">
                        <div class="form-group">
                            <label>Role</label>
                            <input class="form-control" type="text" name="txtrole" placeholder="Enter Role Name" required>
                        </div>

                        <div class="clearfix">
                            <div class="pull-left">
                                <p class="mb-30 font-14"></p>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-success" type="submit" name="btnregister">Register</button>
                                <button class="btn btn-danger" type="reset" name="btncancel">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Role Form End -->
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

     <?php include('script.php'); ?>
</body>
</html>
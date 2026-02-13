<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/admin/role_update.php");

    if (isset($_REQUEST['rid'])) 
    {
        $roleID=$_REQUEST['rid'];
        $select=mysqli_query($connection,"SELECT * FROM role 
                                        WHERE RoleID='$roleID'");
        $data=mysqli_fetch_array($select);

        $role=$data['Role'];
        $status=$data['Status'];
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
                <!-- Page Header Start -->
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
                                    <li class="breadcrumb-item active" aria-current="page">Role Update</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header End -->

                <!-- Update Role Form Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Update Form</h4>
                            <p class="mb-30">Please update role information</p>
                        </div>
                        <div class="pull-right">
                            <a href="role_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="role_update.php" method="post">
                        <input type="hidden" name="txtRID" value="<?php echo $roleID ?>">
                        <div class="form-group">
                            <label>Role</label>
                            <input class="form-control" type="text" name="txtrole" value="<?php echo $role ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="cbostatus" required/>
                                <option><?php echo $status ?></option>
                                <?php 
                                    if($status!="Active")
                                    {
                                        echo"<option value='Active'>Active</option>";
                                    }

                                    if($status!="Inactive")
                                    {
                                        echo"<option value='Inactive'>Inactive</option>";
                                    }
                                 ?>
                            </select>
                        </div>

                        <div class="clearfix">
                            <div class="pull-left">
                                <p class="mb-30 font-14"></p>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-success" type="submit" name="btnupdate">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Update Role Form End -->
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

     <?php include('script.php'); ?>
</body>
</html>
<?php 
    session_start();
    include("../includes/connect.php");
    include("../includes/AutoID_Functions.php");
    include("../includes/Browsing_Functions.php");
    recordbrowse("http://localhost/UniEnroll/portal/department.php");

if (isset($_POST['btnregister'])) 
{
    $DID=$_POST['txtDID'];
    $name=$_POST['txtname'];
    $year=$_POST['txtyear'];
    $faculties=$_POST['cbofname'];
    $status="Active";

    // Validate founded year (YYYY)
    if (!preg_match('/^[0-9]{4}$/', $year)) {
        echo "<script>alert('Founded Year must be a 4-digit year like 1999 or 2026')</script>";
        echo "<script>location='department.php'</script>";
        exit();
    }

    // Get faculties founded year
    $facQuery = mysqli_query($connection, "
        SELECT Founded_year 
        FROM faculties 
        WHERE FacultiesID = '$faculties'
    ");
    $facData = mysqli_fetch_assoc($facQuery);
    $facultyYear = $facData['Founded_year'];

    // Compare founded years
    if ($year < $facultyYear) {
        echo "<script>alert('Department cannot be founded before its faculty (Faculty founded: $facultyYear)')</script>";
        echo "<script>location='department.php'</script>";
        exit();
    }

    $select=mysqli_query($connection,"SELECT * FROM department 
                                                    WHERE Name='$name'");
    $count=mysqli_num_rows($select);
    if ($count==0) 
    {
        $insert=mysqli_query($connection,"INSERT INTO department(DepartmentID, FacultiesID, Name, Founded_year, Status) 
                                                        VALUES('$DID', '$faculties','$name', '$year','$status')");
        if ($insert) 
        {
            echo "<script>alert('Department Register Success!')</script>";
            echo "<script>location='department_list.php'</script>";
        }

        else
        {
            echo mysqli_error($connection);
        }
    }

    else
    {
        echo "<script>alert('Department Already Exist!')</script>";
        echo "<script>location='department.php'</script>";
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
                <!-- Page Header Start -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Department</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="department_list.php">Department List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Department Register</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header End -->

                <!-- Department Form Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Registration Form</h4>
                            <p class="mb-30">Please fill department information</p>
                        </div>
                        <div class="pull-right">
                            <a href="department_list.php" class="btn btn-primary btn-sm scroll-click" role="button"><i class="icon-copy ti-angle-double-left"></i></a>
                        </div>
                    </div>

                    <form action="department.php" method="post">
                        <input type="hidden" name="txtDID" value="<?php echo AutoID('department', 'DepartmentID', 'DID-', 4) ?>">
                        <div class="form-group">
                            <label>Department Name</label>
                            <input class="form-control" type="text" name="txtname" placeholder="e.g. Computer Science, Civil Engineering, Business Administration" required>
                        </div>

                        <div class="form-group">
                            <label>Faculties Name</label>
                            <select class="selectpicker form-control" name="cbofname" required/>
                                <option value="">Choose Faculties Name</option>
                                
                                <optgroup label="Active">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM faculties");
                                        $count=mysqli_num_rows($select);
                                        for ($i=0; $i < $count; $i++) 
                                        { 
                                            $data=mysqli_fetch_array($select);
                                            $facultiesID=$data['FacultiesID'];
                                            $facultiesName=$data['Name'];
                                            $facultiesYear=$data['Founded_year'];
                                            $facultiesStatus=$data['Status'];

                                            if($facultiesStatus=="Active")
                                            {
                                                echo "<option value='$facultiesID'>$facultiesName, $facultiesYear</option>"; 
                                            }
                                        }
                                     ?>
                                </optgroup>

                                <optgroup label="Inactive">
                                    <?php 
                                        $select=mysqli_query($connection,"SELECT * FROM faculties");
                                        $count=mysqli_num_rows($select);
                                        for ($i=0; $i < $count; $i++) 
                                        { 
                                            $data=mysqli_fetch_array($select);
                                            $facultiesID=$data['FacultiesID'];
                                            $facultiesName=$data['Name'];
                                            $facultiesYear=$data['Founded_year'];
                                            $facultiesStatus=$data['Status'];

                                            if($facultiesStatus=="Inactive")
                                            {
                                                echo "<option disabled>$facultiesName, $facultiesYear</option>";
                                            }
                                        }
                                     ?>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Founded Year</label>
                            <input class="form-control" type="text" name="txtyear" placeholder="YYYY (e.g. 2026)" pattern="[0-9]{4}" maxlength="4" title="Please enter a valid 4-digit year (e.g. 1999, 2026)" required>
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
                <!-- Department Form End -->
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

     <?php include('script.php'); ?>
</body>
</html>
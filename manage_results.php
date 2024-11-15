<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/home.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="./css/form.css">
    <title>Dashboard</title>
</head>
<body>
        
    <div class="title">
        <a href="dashboard.php"><img src="./images/iitjodhpur.png" alt="" class="logo"></a>
        <span class="heading">Dashboard</span>
        <a href="logout.php" style="color: white"><span class="fa fa-sign-out fa-2x">Logout</span></a>
    </div>

    <div class="nav">
        <ul>
            <li class="dropdown" onclick="toggleDisplay('1')">
                <a href="" class="dropbtn">Classes &nbsp
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-content" id="1">
                    <a href="add_classes.php">Add Class</a>
                    <a href="manage_classes.php">Manage Class</a>
                </div>
            </li>
            <li class="dropdown" onclick="toggleDisplay('2')">
                <a href="#" class="dropbtn">Students &nbsp
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-content" id="2">
                    <a href="add_students.php">Add Students</a>
                    <a href="manage_students.php">Manage Students</a>
                </div>
            </li>
            <li class="dropdown" onclick="toggleDisplay('3')">
                <a href="#" class="dropbtn">Results &nbsp
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-content" id="3">
                    <a href="add_results.php">Add Results</a>
                    <a href="manage_results.php">Manage Results</a>
                    <a href="reports.php">reports</a>
                </div>
            </li>
        </ul>
    </div>

    <div class="main">
        <br><br>
        <form action="" method="post">
            <fieldset>
                <legend>Delete Result</legend>
                <?php
                    include('init.php');
                    include('session.php');
                    
                    $class_result=mysqli_query($conn,"SELECT `name` FROM `class`");
                        echo '<select name="class_name">';
                        echo '<option selected disabled>Select Class</option>';
                    while($row = mysqli_fetch_array($class_result)){
                        $display=$row['name'];
                        echo '<option value="'.$display.'">'.$display.'</option>';
                    }
                    echo'</select>'
                ?>
                <input type="text" name="rno" placeholder="Roll No">
                <input type="submit" value="Delete">
            </fieldset>
        </form>
        <br><br>

        <form action="" method="post">
            <fieldset>
                <legend>Update Result</legend>
                
                <?php
                    $class_result=mysqli_query($conn,"SELECT `name` FROM `class`");
                        echo '<select name="class">';
                        echo '<option selected disabled>Select Class</option>';
                    while($row = mysqli_fetch_array($class_result)){
                        $display=$row['name'];
                        echo '<option value="'.$display.'">'.$display.'</option>';
                    }
                    echo'</select>'
                ?>
                
                <input type="text" name="rn" placeholder="Roll No">
                <input type="text" name="p1" id="" placeholder="Paper 1">
                <input type="text" name="p2" id="" placeholder="Paper 2">
                <input type="text" name="p3" id="" placeholder="Paper 3">
                <input type="text" name="p4" id="" placeholder="Paper 4">
                <input type="text" name="p5" id="" placeholder="Paper 5">
                <input type="submit" value="Update">
            </fieldset>
        </form>
    </div>

    <!-- <div class="footer">
        <span>Designed & Coded By Jibin Thomas</span>
    </div> -->
    
</body>
</html>



<?php
    include('init.php');
    include('session.php');

    if (isset($_POST['class_name'], $_POST['rno'])) {
        $class_name = $_POST['class_name'];
        $rno = $_POST['rno'];

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Try to delete the result for the given class and roll number
            $delete_sql = "DELETE FROM result WHERE rno='$rno' AND class='$class_name'";
            if (!mysqli_query($conn, $delete_sql)) {
                throw new Exception('Error deleting result');
            }

            // Commit transaction
            mysqli_commit($conn);
            echo '<script language="javascript">';
            echo 'alert("Deleted successfully")';
            echo '</script>';
        } catch (Exception $e) {
            // Rollback transaction in case of error
            mysqli_roll_back($conn);
            echo '<script language="javascript">';
            echo 'alert("Error: ' . $e->getMessage() . '")';
            echo '</script>';
        }
    }

    if (isset($_POST['rn'], $_POST['p1'], $_POST['p2'], $_POST['p3'], $_POST['p4'], $_POST['p5'], $_POST['class'])) {
        $rno = $_POST['rn'];
        $class_name = $_POST['class'];
        $p1 = (int)$_POST['p1'];
        $p2 = (int)$_POST['p2'];
        $p3 = (int)$_POST['p3'];
        $p4 = (int)$_POST['p4'];
        $p5 = (int)$_POST['p5'];

        // Calculate total marks and percentage
        $marks = $p1 + $p2 + $p3 + $p4 + $p5;
        $percentage = $marks / 5;

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Prepare the SQL query for updating the result
            $update_sql = "UPDATE result SET p1='$p1', p2='$p2', p3='$p3', p4='$p4', p5='$p5', marks='$marks', percentage='$percentage' WHERE rno='$rno' AND class='$class_name'";

            // Execute the update query
            if (!mysqli_query($conn, $update_sql)) {
                throw new Exception('Error updating result');
            }

            // Commit transaction if update is successful
            mysqli_commit($conn);
            echo '<script language="javascript">';
            echo 'alert("Updated successfully")';
            echo '</script>';
        } catch (Exception $e) {
            // Rollback transaction in case of error
            mysqli_roll_back($conn);
            echo '<script language="javascript">';
            echo 'alert("Error: ' . $e->getMessage() . '")';
            echo '</script>';
        }
    }
?>

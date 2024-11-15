<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" type="text/css" href="./css/form.css" media="all">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.css">
    <title>Add Students</title>
</head>
<body>
        
    <div class="title">
        <a href="dashboard.php"><img src="./images/iitjodhpur.jpg" alt="" class="logo"></a>
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
        <form action="" method="post">
            <fieldset>
                <legend>Add Student</legend>
                <input type="text" name="student_name" placeholder="Student Name">
                <input type="text" name="roll_no" placeholder="Roll No">
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
                <input type="submit" value="Submit">
            </fieldset>
        </form>
    </div>

    <div class="footer">
        <!-- <span>&copy Designed & Coded By Jibin Thomas</span> -->
    </div>
</body>
</html>
<?php
    include('init.php');
    include('session.php');

    if (isset($_POST['student_name'], $_POST['roll_no'], $_POST['class_name'])) {
        $name = $_POST['student_name'];
        $rno = $_POST['roll_no']; // Actual roll number for database
        $class_name = $_POST['class_name'];

        // Validation
        if (empty($name) || empty($rno) || empty($class_name) || preg_match("/[a-z]/i", $rno) || !preg_match("/^[a-zA-Z ]*$/", $name)) {
            echo '<p class="error">Invalid input detected.</p>';
            exit();
        }

        // Begin Transaction
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        try {
            // Prepare and execute statement
            $stmt = $conn->prepare("INSERT INTO students (name, rno, class_name) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $name, $rno, $class_name); // Correct data types
            $stmt->execute();

            mysqli_commit($conn);
            echo '<script>alert("Student added successfully");</script>';
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo '<script>alert("Error adding student");</script>';
        }

        // Optional: Mask roll number for display
        $masked_rno = substr($rno, 0, -2) . '**';
        echo "<p>Student $name (Roll No: $masked_rno) added to class $class_name.</p>";
    }
?>

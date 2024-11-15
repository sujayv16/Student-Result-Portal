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
            <legend>Enter Marks</legend>

                <?php
                    include("init.php");
                    include("session.php");

                    $select_class_query = "SELECT `name` FROM `class`";
                    $class_result = mysqli_query($conn, $select_class_query);
                    // Select class dropdown
                    echo '<select name="class_name">';
                    echo '<option selected disabled>Select Class</option>';
                    
                    while ($row = mysqli_fetch_array($class_result)) {
                        $display = $row['name'];
                        echo '<option value="'.$display.'">'.$display.'</option>';
                    }
                    echo '</select>';                      
                ?>

                <input type="text" name="rno" placeholder="Roll No">
                <input type="text" name="p1" placeholder="Paper 1">
                <input type="text" name="p2" placeholder="Paper 2">
                <input type="text" name="p3" placeholder="Paper 3">
                <input type="text" name="p4" placeholder="Paper 4">
                <input type="text" name="p5" placeholder="Paper 5">
                <input type="submit" value="Submit">
            </fieldset>
        </form>
    </div>
</body>
</html>

<?php
include('init.php');
include('session.php');

if (isset($_POST['rno'], $_POST['p1'], $_POST['p2'], $_POST['p3'], $_POST['p4'], $_POST['p5'])) {
    $rno = $_POST['rno'];
    $class_name = $_POST['class_name'] ?? null;
    $p1 = (int)$_POST['p1'];
    $p2 = (int)$_POST['p2'];
    $p3 = (int)$_POST['p3'];
    $p4 = (int)$_POST['p4'];
    $p5 = (int)$_POST['p5'];

    $marks = $p1 + $p2 + $p3 + $p4 + $p5;
    $percentage = $marks / 5;

    // Validation
    if (empty($class_name) || empty($rno) || $p1 > 100 || $p2 > 100 || $p3 > 100 || $p4 > 100 || $p5 > 100 || $p1 < 0 || $p2 < 0 || $p3 < 0 || $p4 < 0 || $p5 < 0) {
        echo '<p class="error">Invalid inputs detected.</p>';
        exit();
    }

    // Create index on result table if not exists (rno, class)
    $check_index_query = "SHOW INDEXES FROM result WHERE Key_name = 'idx_result_rno_class'";
    $result = mysqli_query($conn, $check_index_query);
    
    if (mysqli_num_rows($result) == 0) {
        // Create the index if it doesn't exist
        $create_index_query = "CREATE INDEX idx_result_rno_class ON result(rno, class)";
        mysqli_query($conn, $create_index_query); // Execute index creation
    }

    // Start transaction
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
    try {
        // Fetch student name
        $stmt_name = $conn->prepare("SELECT name FROM students WHERE rno = ? AND class_name = ?");
        $stmt_name->bind_param("is", $rno, $class_name);
        $stmt_name->execute();
        $stmt_name->store_result();
        $stmt_name->bind_result($display);
        $stmt_name->fetch();

        // Debugging: Output the retrieved student name
        echo "Retrieved student name: " . $display . "<br>";

        // Check if the student exists
        if (empty($display)) {
            throw new Exception("Student not found.");
        }

        // Savepoint for partial rollback
        mysqli_query($conn, "SAVEPOINT add_result");

        // Insert the result
        $stmt_result = $conn->prepare("INSERT INTO result (name, rno, class, p1, p2, p3, p4, p5, marks, percentage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_result->bind_param("sisiiiiiii", $display, $rno, $class_name, $p1, $p2, $p3, $p4, $p5, $marks, $percentage);
        $stmt_result->execute();

        // Commit transaction
        mysqli_commit($conn);
        echo '<script>alert("Result added successfully");</script>';
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo '<script>alert("Error adding result: ' . $e->getMessage() . '");</script>';
    }
}
?>









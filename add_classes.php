<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/form.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.css">
    <title>Add Class</title>
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
                <legend>Add Class</legend>
                <input type="text" name="class_name" placeholder="Class Name">
                <input type="text" name="class_id" placeholder="Class ID">
                <input type="submit" value="Submit">
            </fieldset>        
        </form>
    </div>

    <div class="footer"></div>
</body>
</html>

<?php 
    include('init.php');
    include('session.php');

    if (isset($_POST['class_name'], $_POST['class_id'])) {
        $name = $_POST["class_name"];
        $id = $_POST["class_id"];

        // Validation
        if (empty($name) || empty($id) || preg_match("/[a-z]/i", $id)) {
            if (empty($name)) echo '<p class="error">Please enter class</p>';
            if (empty($id)) echo '<p class="error">Please enter class id</p>';
            if (preg_match("/[a-z]/i", $id)) echo '<p class="error">Please enter valid class id</p>';
            exit();
        }

        // Check if the index exists before creating it
        $check_index_query = "SHOW INDEXES FROM class WHERE Key_name = 'idx_class_id'";
        $result = mysqli_query($conn, $check_index_query);
        
        if (mysqli_num_rows($result) == 0) {
            // Create the index if it doesn't exist
            $create_index_query = "CREATE INDEX idx_class_id ON class(id)";
            mysqli_query($conn, $create_index_query); // Execute index creation
        }

        // Start transaction with SERIALIZABLE isolation for concurrency control
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        try {
            $stmt = $conn->prepare("INSERT INTO class (name, id) VALUES (?, ?)");
            $stmt->bind_param("si", $name, $id);
            $stmt->execute();

            // Commit the transaction
            mysqli_commit($conn);
            echo '<script>alert("Class added successfully");</script>';
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo '<script>alert("Error adding class");</script>';
        }
    }
?>


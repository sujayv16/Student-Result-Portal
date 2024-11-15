<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/home.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" type='text/css' href="css/manage.css">
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
    <?php
    include('init.php');
    include('session.php');

    // Check if a delete request was made
    if (isset($_GET['delete_rno'])) {
        $delete_rno = mysqli_real_escape_string($conn, $_GET['delete_rno']);
        $delete_sql = "DELETE FROM students WHERE rno = '$delete_rno'";
        if (mysqli_query($conn, $delete_sql)) {
            echo "Student deleted successfully.";
        } else {
            echo "Error deleting student: " . mysqli_error($conn);
        }
    }

    // Check if a search query or class_name is provided
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $class_name = isset($_GET['class_name']) ? mysqli_real_escape_string($conn, $_GET['class_name']) : '';

    // Base SQL query to fetch students grouped by class
    $sql = "SELECT name, rno, class_name FROM students";
    if (!empty($class_name)) {
        $sql .= " WHERE class_name = '$class_name'";
    } elseif (!empty($search)) {
        $sql .= " WHERE MATCH(name, class_name) AGAINST ('$search' IN NATURAL LANGUAGE MODE) 
                  OR name LIKE '%$search%' OR class_name LIKE '%$search%'";
    }
    $sql .= " ORDER BY class_name";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $current_class = '';
        echo "<div style='margin-bottom: 20px;'>
                <form method='GET' action='manage_students.php'>
                    <input type='text' name='search' placeholder='Search for student name or class name'>
                    <input type='submit' value='Search'>
                </form>
              </div>";

        while ($row = mysqli_fetch_array($result)) {
            // Start a new table for each class
            if ($current_class != $row['class_name']) {
                if ($current_class != '') {
                    echo "</table>";  // Close the previous class table
                }
                $current_class = $row['class_name'];
                echo "<h3>Class: $current_class</h3>
                      <table border='1'>
                          <tr><th>NAME</th><th>ROLL NO</th><th>CLASS</th><th>Actions</th></tr>";
            }
            
            // Mask the roll number for privacy
            $masked_rno = substr($row['rno'], 0, -2) . '**';
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$masked_rno}</td>
                    <td>{$row['class_name']}</td>
                    <td><a href='manage_students.php?delete_rno={$row['rno']}' onclick=\"return confirm('Are you sure you want to delete this student?');\">Delete</a></td>
                  </tr>";
        }
        echo "</table>"; // Close the last class table
    } else {
        echo "No matching students found.";
    }
?>
    </div>

    <div class="footer">
        <!-- <span>Designed & Coded By Jibin Thomas</span> -->
    </div>
</body>
</html>

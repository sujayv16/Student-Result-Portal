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
        <a href="dashboard.php"><img src="./images/iitjodhpur.jpg." alt="" class="logo"></a>
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
        <!-- Search form -->
<form method="GET" action="manage_classes.php">
    <input type="text" name="search" placeholder="Search for class name">
    <input type="submit" value="Search">
</form>
    <?php
    include('init.php');
    include('session.php');

    // Check if a search query is provided
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

    // Search with FULLTEXT and LIKE fallback
    $sql = "SELECT name, id FROM class";
    if (!empty($search)) {
        $sql .= " WHERE MATCH(name) AGAINST ('$search' IN NATURAL LANGUAGE MODE) 
                  OR name LIKE '%$search%'";
    }

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>
            <caption>Manage Classes</caption>
            <tr><th>ID</th><th>NAME</th><th>Actions</th></tr>";

        while ($row = mysqli_fetch_array($result)) {
            $class_name = urlencode($row['name']);
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td><a href='manage_students.php?class_name=$class_name'>View Students</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No matching classes found.";
    }
?>


        
    </div>

</body>
</html>


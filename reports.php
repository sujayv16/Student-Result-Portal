<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/home.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/manage.css">
    <style>
        /* Flexbox for arranging images in the center */
        .images-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
            align-items: center; /* Ensure vertical centering of images */
        }

        .images-row div {
            text-align: center; /* Center the image captions */
        }

        .images-row img {
            width: 300px; /* Fixed width for the images */
            height: auto;
        }
    </style>
    <title>Report</title>
</head>
<body>
        
    <div class="title">
        <a href="dashboard.php"><img src="./images/iitjodhpur.jpg" alt="" class="logo"></a>
        <span class="heading">Report</span>
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
                </div>
            </li>
        </ul>
    </div>

    <div class="main">
        <!-- Images Row -->
        <div class="images-row">
            <!-- Top Performers Image -->
            <div>
                <h2>Top Performers</h2>
                <img src="./reports/top_performers.png" alt="Top Performers">
            </div>

            <!-- Class-Wise Average Marks Image -->
            <div>
                <h2>Class-Wise Average Marks</h2>
                <img src="./reports/class_avg.png" alt="Class-wise Average Marks">
            </div>

            <!-- Pass/Fail Distribution Image -->
            <div>
                <h2>Pass/Fail Distribution</h2>
                <img src="./reports/pass_fail_distribution.png" alt="Pass/Fail Distribution">
            </div>
        </div>

        <!-- Top Performers Table -->
        <h2>Top Performers</h2>
        <?php
        include('init.php');
        include('session.php');

        // Fetch top performers (Top 5 based on marks)
        $top_performers_sql = "SELECT result.rno, students.name, result.marks, result.class 
                               FROM result 
                               JOIN students ON result.rno = students.rno 
                               ORDER BY result.marks DESC LIMIT 5";
        $top_performers_result = mysqli_query($conn, $top_performers_sql);

        if (mysqli_num_rows($top_performers_result) > 0) {
            echo "<table>
                    <tr><th>Rank</th><th>Roll No</th><th>Name</th><th>Marks</th></tr>";
            $rank = 1;
            while ($row = mysqli_fetch_assoc($top_performers_result)) {
                echo "<tr>
                        <td>$rank</td>
                        <td>{$row['rno']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['marks']}</td>
                    </tr>";
                $rank++;
            }
            echo "</table>";
        } else {
            echo "<p>No top performers found.</p>";
        }
        ?>

        <!-- Class-Wise Average Marks Table -->
        <h2>Class-Wise Average Marks</h2>
        <?php
        // Fetch class-wise average marks
        $class_avg_sql = "SELECT result.class, AVG(result.marks) AS avg_marks 
                          FROM result 
                          GROUP BY result.class";
        $class_avg_result = mysqli_query($conn, $class_avg_sql);

        if (mysqli_num_rows($class_avg_result) > 0) {
            echo "<table>
                    <tr><th>Class</th><th>Average Marks</th></tr>";
            while ($row = mysqli_fetch_assoc($class_avg_result)) {
                echo "<tr>
                        <td>{$row['class']}</td>
                        <td>" . number_format($row['avg_marks'], 2) . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No class averages found.</p>";
        }
        ?>

        <!-- Students Who Failed Table -->
        <h2>Students Who Failed (Below 40%)</h2>
        <?php
        // Fetch students who failed (below 40% marks)
        $failed_students_sql = "SELECT result.rno, students.name, result.marks, result.class 
                               FROM result 
                               JOIN students ON result.rno = students.rno 
                               WHERE result.percentage < 40";
        $failed_students_result = mysqli_query($conn, $failed_students_sql);

        if (mysqli_num_rows($failed_students_result) > 0) {
            echo "<table>
                    <tr><th>Roll No</th><th>Name</th><th>Marks</th><th>Class</th></tr>";
            while ($row = mysqli_fetch_assoc($failed_students_result)) {
                echo "<tr>
                        <td>{$row['rno']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['marks']}</td>
                        <td>{$row['class']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No failed students found.</p>";
        }
        ?>

        <!-- Students with Missing Results Table -->
        <h2>Students with Missing Results</h2>
        <?php
        // Fetch students whose results are missing
        $missing_results_sql = "SELECT students.rno, students.name, students.class_name 
                                FROM students 
                                WHERE students.rno NOT IN (SELECT result.rno FROM result)";
        $missing_results_result = mysqli_query($conn, $missing_results_sql);

        if (mysqli_num_rows($missing_results_result) > 0) {
            echo "<table>
                    <tr><th>Roll No</th><th>Name</th><th>Class</th></tr>";
            while ($row = mysqli_fetch_assoc($missing_results_result)) {
                echo "<tr>
                        <td>{$row['rno']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['class_name']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No missing results found.</p>";
        }

        // Close database connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>

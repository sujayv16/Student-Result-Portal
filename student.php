<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/student.css">
    <title>Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #4CAF50;
        }
        .details, .result {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .details span {
            font-weight: bold;
        }
        .main {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .s1, .s2 {
            width: 45%;
        }
        .s1 p, .s2 p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }
        .s2 p {
            font-weight: bold;
            color: #2c3e50;
        }
        .result p {
            font-size: 18px;
            font-weight: bold;
            color: #2980b9;
        }
        .button {
            text-align: center;
            margin-top: 20px;
        }
        .button button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .button button:hover {
            background-color: #45a049;
        }
        .rank {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
        include("init.php");

        // Start read-only transaction
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_ONLY); // Use a read-only transaction

        if(!isset($_GET['class']))
            $class = null;
        else
            $class = $_GET['class'];
        
        $rn = $_GET['rn'];

        // Validation: Ensure class and roll number are provided and valid
        if (empty($class) or empty($rn) or preg_match("/[a-z]/i", $rn)) {
            if(empty($class))
                echo '<p class="error">Please select your class</p>';
            if(empty($rn))
                echo '<p class="error">Please enter your roll number</p>';
            if(preg_match("/[a-z]/i", $rn))
                echo '<p class="error">Please enter a valid roll number</p>';
            exit();
        }

        // Query to fetch student's name using roll number and class
        $name_sql = mysqli_query($conn, "SELECT `name` FROM `students` WHERE `rno`='$rn' AND `class_name`='$class'");
        $name = '';
        while($row = mysqli_fetch_assoc($name_sql)) {
            $name = $row['name'];
        }

        // Query to fetch results for the student
        $result_sql = mysqli_query($conn, "SELECT `p1`, `p2`, `p3`, `p4`, `p5`, `marks`, `percentage` FROM `result` WHERE `rno`='$rn' AND `class`='$class'");

        // If no result found
        if (mysqli_num_rows($result_sql) == 0) {
            echo "No result found for this roll number in this class.";
            exit();
        }

        // Fetching the result data
        while ($row = mysqli_fetch_assoc($result_sql)) {
            $p1 = $row['p1'];
            $p2 = $row['p2'];
            $p3 = $row['p3'];
            $p4 = $row['p4'];
            $p5 = $row['p5'];
            $marks = $row['marks'];  // Corrected variable to $marks
            $percentage = $row['percentage'];
        }

        // Query to calculate rank in the class
        $rank_sql = mysqli_query($conn, "SELECT `rno`, `marks` FROM `result` WHERE `class`='$class' ORDER BY `marks` DESC");

        $rank = 1;
        while ($row = mysqli_fetch_assoc($rank_sql)) {
            if ($row['rno'] == $rn) {
                break; // When we find the student's roll number, we break out of the loop
            }
            $rank++; // Increment rank for each student
        }

        // Commit read-only transaction
        mysqli_commit($conn); // Committing the transaction

        // Close the database connection
        mysqli_close($conn);
    ?>

    <div class="container">
        <div class="header">
            <h1>Student Result</h1>
        </div>
        
        <div class="details">
            <span>Name:</span> <?php echo $name ?> <br>
            <span>Class:</span> <?php echo $class; ?> <br>
            <span>Roll No:</span> <?php echo $rn; ?> <br>
            <div class="rank">Rank: <?php echo $rank; ?></div> <!-- Displaying rank -->
        </div>

        <div class="main">
            <div class="s1">
                <p><strong>Subjects</strong></p>
                <p>Paper 1</p>
                <p>Paper 2</p>
                <p>Paper 3</p>
                <p>Paper 4</p>
                <p>Paper 5</p>
            </div>
            <div class="s2">
                <p><strong>Marks</strong></p>
                <?php echo '<p>'.$p1.'</p>';?>
                <?php echo '<p>'.$p2.'</p>';?>
                <?php echo '<p>'.$p3.'</p>';?>
                <?php echo '<p>'.$p4.'</p>';?>
                <?php echo '<p>'.$p5.'</p>';?>
            </div>
        </div>

        <div class="result">
            <?php echo '<p>Total Marks:&nbsp'.$marks.'</p>';?> <!-- Corrected to $marks -->
            <?php echo '<p>Percentage:&nbsp'.$percentage.'%</p>'; ?>
        </div>

        <div class="button">
            <button onclick="window.print()">Print Result</button>
        </div>
    </div>
</body>
</html>

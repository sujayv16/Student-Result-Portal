<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body>
    <div class="title">
        <span>Student Result Portal - IIT Jodhpur</span>
    </div>

    <div class="main">
        <div class="login">
            <form action="" method="post" name="login">
                <fieldset>
                    <legend class="heading">Admin Login</legend>
                    <input type="text" name="userid" placeholder="Email" autocomplete="off">
                    <input type="password" name="password" placeholder="Password" autocomplete="off">
                    <input type="submit" value="Login">
                </fieldset>
            </form>    
        </div>
        <div class="search">
            <form action="./student.php" method="get">
                <fieldset>
                    <legend class="heading">For Students</legend>

                    <?php
                        include('init.php');

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
                    <input type="submit" value="Get Result">
                </fieldset>
            </form>
        </div>
    </div>

</body>
</html>

<?php
    include("init.php");
    session_start();

    if (isset($_POST["userid"], $_POST["password"])) {
        $username = $_POST["userid"];
        $password = $_POST["password"];

        // Step 1: Preventing SQL Injection with prepared statements
        // Fetch user details including failed attempts and lockout status
        $stmt = $conn->prepare("SELECT userid, password, failed_attempts, lock_until FROM admin_login WHERE userid = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Debugging: Check user details
            // echo "Lock until: " . $row['lock_until'] . "<br>";
            // echo "Failed attempts: " . $row['failed_attempts'] . "<br>";

            // Step 2: Check if account is locked
            if (!empty($row['lock_until']) && strtotime($row['lock_until']) > time()) {
                $lock_time_remaining = strtotime($row['lock_until']) - time();
                echo '<script language="javascript">';
                echo 'alert("Account locked. Try again after ' . gmdate("H:i:s", $lock_time_remaining) . '")';
                echo '</script>';
                exit();
            }

            // Step 3: Verify password
            if (password_verify($password, $row['password'])) {
                // Successful login, reset failed attempts and lock_until if login is successful
                $stmt = $conn->prepare("UPDATE admin_login SET failed_attempts = 0, lock_until = NULL WHERE userid = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();

                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Set session for login_user
                $_SESSION['login_user'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password, increment failed attempts
                $failed_attempts = $row['failed_attempts'] + 1;
                $lock_duration = 15 * 60; // Lockout for 15 minutes
                $lock_until = null;

                // Lock account after 3 failed attempts
                if ($failed_attempts >= 3) {
                    $lock_until = date('Y-m-d H:i:s', time() + $lock_duration);
                    echo '<script language="javascript">';
                    echo 'alert("Your account has been locked. Please try again after 15 minutes.")';
                    echo '</script>';
                }

                // Step 5: Update failed attempts and lock_until in the database
                $stmt = $conn->prepare("UPDATE admin_login SET failed_attempts = ?, lock_until = ? WHERE userid = ?");
                $stmt->bind_param("iss", $failed_attempts, $lock_until, $username);
                $stmt->execute();

                // Debugging - Check if the update was successful
                if ($stmt->affected_rows > 0) {
                    // echo "Failed attempts updated to " . $failed_attempts . "<br>";
                    // if ($lock_until) {
                    //     echo "Account locked until: " . $lock_until . "<br>";
                    // }
                } else {
                    echo "Failed attempts not updated. Please try again.";
                }

                echo '<script language="javascript">';
                echo 'alert("Invalid Username or Password. You have ' . (3 - $failed_attempts) . ' attempts left.")';
                echo '</script>';
            }
        } else {
            echo '<script language="javascript">';
            echo 'alert("Invalid Username or Password")';
            echo '</script>';
        }
    }
?>

<?php
    // Ensure session_start is only called if no session is active
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Key Distribution Center (KDC) - Generate a secure session key if not already set
    if (!isset($_SESSION['session_key'])) {
        $_SESSION['session_key'] = bin2hex(random_bytes(32));  // Generate a secure random session key (256-bit)
    }

    // Simulate storing session data in a distributed cache system (e.g., Redis or Memcached)
    // If you are in a distributed environment, you should connect to Redis/Memcached to store the session key securely

    // For example, using Redis for centralized session storage (replace with your Redis connection details)
    /*
    $redis = new Redis();
    $redis->connect('localhost', 6379);
    $redis->set('session_key_' . session_id(), $_SESSION['session_key']);
    */

    // Using MySQL database to check the logged-in user (traditional session management)
    include('init.php'); // Make sure to include init.php to have database connection

    $db = mysqli_select_db($conn, 'srms');
    $user_check = $_SESSION['login_user'];  // Get the user ID stored in session

    // Query to fetch the user from the database based on session information
    $ses_sql = mysqli_query($conn, "SELECT userid FROM admin_login WHERE userid= '$user_check'");
    $row = mysqli_fetch_array($ses_sql);

    $login_session = $row['userid'];  // Assign the user ID to the session

    // Validate the session key for added security
    if (!isset($_SESSION['session_key']) || empty($_SESSION['session_key'])) {
        echo '<script>alert("Session key is missing or invalid! Please log in again.")</script>';
        session_destroy();  // Destroy the session if the key is invalid
        header("Location: login.php");  // Redirect to login page
        exit();
    }

    // Check if the user is logged in, if not, redirect to login page
    if (!isset($_SESSION['login_user'])) {
        header("Location: login.php");
        exit();
    }

    // Clustered Session Management (Simulated) - Store session data in Redis/Memcached if needed for distributed setups
    /*
    // Example Redis session storage (if using Redis):
    $redis = new Redis();
    $redis->connect('localhost', 6379);
    $redis->set('session_key_' . session_id(), $_SESSION['session_key']);
    */

    // If your system is using Memcached, you can similarly store session data in Memcached:
    /*
    $memcache = new Memcache;
    $memcache->connect('localhost', 11211);
    $memcache->set('session_key_' . session_id(), $_SESSION['session_key']);
    */

    // Additional improvements can include automatic session token expiration, logging, etc.
?>

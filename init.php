<?php
    // Prevent multiple inclusions of init.php
    if (!defined('INIT_PHP_LOADED')) {
        define('INIT_PHP_LOADED', true);

        // Database connection configuration
        $servername = "localhost";
        $username = "root";
        $password = "Sekhar@1629";
        $database = "srms";

        // Establish a MySQL connection with error handling
        $conn = mysqli_connect($servername, $username, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Enable ACID properties with strict transaction isolation level
        mysqli_autocommit($conn, false);  // Turn off auto-commit to control transactions
        mysqli_query($conn, "SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;");

        /**
         * Function to execute queries with retry mechanism.
         * It retries the query if a deadlock occurs, with a limited number of retries.
         * @param mysqli $conn The database connection.
         * @param string $query The SQL query to execute.
         * @param int $retries Number of retries for deadlock resolution.
         * @return mixed The query result or false if retries are exhausted.
         */
        if (!function_exists('executeWithRetry')) {
            function executeWithRetry($conn, $query, $retries = 3) {
                $attempt = 0;
                while ($attempt < $retries) {
                    try {
                        // Begin transaction
                        mysqli_begin_transaction($conn);

                        // Execute the query
                        $result = mysqli_query($conn, $query);

                        // Commit if successful
                        if ($result) {
                            mysqli_commit($conn);
                            return $result;
                        } else {
                            throw new Exception("Query failed: " . mysqli_error($conn));
                        }
                    } catch (Exception $e) {
                        // Rollback on error
                        mysqli_rollback($conn);

                        // Check if error is deadlock, retry if necessary
                        if (mysqli_errno($conn) == 1213) {  // Error 1213: Deadlock found
                            $attempt++;
                            sleep(1);  // Optional: Small delay before retrying
                        } else {
                            // Log error and stop retries
                            error_log("Database error: " . $e->getMessage());
                            break;
                        }
                    }
                }
                return false;  // Return false if retries are exhausted or error is not recoverable
            }
        }

        /**
         * Execute a transactional query with savepoints.
         * This function uses savepoints within a transaction to handle partial rollbacks.
         * @param mysqli $conn The database connection.
         * @param string $queries Array of SQL queries to execute with savepoints.
         * @return bool Returns true if all queries succeed, false otherwise.
         */
        if (!function_exists('executeWithSavepoints')) {
            function executeWithSavepoints($conn, $queries) {
                try {
                    // Begin transaction
                    mysqli_begin_transaction($conn);

                    // Iterate over queries and add savepoints
                    foreach ($queries as $i => $query) {
                        $savepoint = "SAVEPOINT_$i";
                        mysqli_query($conn, "SAVEPOINT $savepoint");

                        $result = mysqli_query($conn, $query);
                        if (!$result) {
                            throw new Exception("Query failed: " . mysqli_error($conn));
                        }
                    }

                    // Commit all if successful
                    mysqli_commit($conn);
                    return true;
                } catch (Exception $e) {
                    // Roll back to the last successful savepoint if error occurs
                    mysqli_rollback($conn);
                    error_log("Transaction error with savepoints: " . $e->getMessage());
                    return false;
                }
            }
        }

        // ODBC adaptability (optional: Uncomment if future ODBC support is needed)
        // $odbc_conn = odbc_connect("Driver={MySQL ODBC 8.0 Driver};Server=$servername;Database=$database;", $username, $password);

        // Close connection at end of script (handled in a specific exit script if needed)
        register_shutdown_function(function() use ($conn) {
            if (is_resource($conn)) { // Check if the connection is still valid
                mysqli_close($conn);
            }
        });
    }
?>

<?php
$password = 'admin123';  // The plain password you want to hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
echo $hashed_password;  // Output the hashed password
?>

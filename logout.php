<?php
session_start();
session_unset();            // Remove all session variables
session_destroy();          // Destroy the session
setcookie("PHPSESSID", "", time() - 3600, "/"); // Optional: force remove session cookie
header("Location: /hostel/index.html"); // Redirect to login/homepage
exit();
?>

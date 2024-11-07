<?php
include('navbar.php');
session_start();
session_unset(); //unset session variables
session_destroy(); //destroy session
echo "You have logged out.";
?>

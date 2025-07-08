<?php
session_start();
include("includes/dbconnection.php"); // Ensure this path is correct for your app
date_default_timezone_set('Africa/Kampala'); // Adjust to your app's timezone if different

$ldate=date( 'd-m-Y h:i:s A', time () );
$email=$_SESSION['email']; // Make sure 'email' is the correct session key for the user's email

// Update the user's logout time in the database
$sql="UPDATE userlog SET logout=:ldate WHERE userEmail = :email ORDER BY id DESC LIMIT 1";
$query=$dbh->prepare($sql);
$query->bindParam(':ldate',$ldate,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR); // Bind email parameter for security
$query->execute();

// Set a success message in session (optional)
$_SESSION['errmsg']="You have successfully logged out.";

// Unset specific session variables (if 'cpmsaid' is relevant to your app)
unset($_SESSION['cpmsaid']);

// Destroy the entire session
session_destroy();

// Redirect the user to the login page or home page
header("location:home.html"); // Adjust this to your actual login/home page
exit(); // Always call exit() after a header redirect
?>

<!-- tambah nanti utk login a href="logout.php">Logout</a-->
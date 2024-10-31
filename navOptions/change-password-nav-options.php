<?php
$user_type = $_SESSION['user_type'];
// Определение массива для хранения параметров навигации
$navOptions = array(
    "Home" => "index.php",
    "About Us" => "aboutUs.php",
    "Book Now" => "booking-form.php",
    // "Sign up" => array(
    //     "Customer" => "signup.php",
    //     // "Airline" => "#"
    // ),
    "Dashboard" => "{$user_type}-dashboard.php",
    "Settings" => array(
        "Change Password" => "change-password.php",
        "Log out" => "logout.php",
        // "Customer" => "login.php",
        // "Airline" => "login.php",
        // "Admin" => "login.php"
    )

);
?>
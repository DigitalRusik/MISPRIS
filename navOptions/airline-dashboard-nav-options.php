<?php
$user_type = $_SESSION['user_type'];
// Определение массива для хранения параметров навигации
$navOptions = array(
    "Домой" => "index.php",
    "О нас" => "aboutUs.php",
    // "Sign up" => array(
    //     "Customer" => "signup.php",
    //     // "Airline" => "#"
    // ),
    "Панель" => "{$user_type}-dashboard.php",
    "Настройки" => array(
        "Поменять пароль" => "change-password.php",
        "Выйти" => "logout.php",
        // "Customer" => "login.php",
        // "Airline" => "login.php",
        // "Admin" => "login.php"
    )

);
?>
<?php
// Определение массива для хранения параметров навигации
$navOptions = array(
    "Домой" => "index.php",
    "Бронировать" => "booking-form.php",
    // "About Us" => "aboutUs.php",
    "Регистрация" => array(
        "Пользователь" => "signup.php",
        // "Airline" => "#"
    ),
    "Войти" => array(
        "Customer" => "login.php",
        "Airline" => "login.php",
        "Admin" => "login.php"
    )
);
?>


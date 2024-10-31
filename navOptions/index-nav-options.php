<?php
// Определение массива для хранения параметров навигации
$navOptions = array(
    "Домой" => "index.php",
    "О нас" => "aboutUs.php",
    "Забронировать" => "booking-form.php",
    "Зарегистрироваться" => array(
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


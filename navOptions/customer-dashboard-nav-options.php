<?php
$user_type = $_SESSION['user_type'];
// Определение массива для хранения параметров навигации
$navOptions = array(
    "Домой" => "index.php",
    "Матчи" => "shedule.php",
    "Турнирная таблица" => "tournamenttable.php",
    "Магазин" => "shopforuser.php",
    "Участники" => "playersforuser.php",
    "Бронировать" => "booking-form.php",
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
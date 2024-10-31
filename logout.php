<?php
// Начало или возобновление сеанса
session_start();

// Проверка, вошел ли пользователь в систему
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Отмена установки всех переменных сеанса
    $_SESSION = array();

    // Завершить сеанс
    session_destroy();

    // Перенаправление пользователя на страницу входа в систему или любую другую желаемую страницу
    header('Location: index.php');
    exit();
} else {
    header('location:index.php');
}
?>
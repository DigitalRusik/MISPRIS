<?php
session_start();
require_once('includes/showMessage.php');
require 'includes/functions.php';

if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
    exit();
}

displaySessionMessage();

if (isset($_POST['flight_but'])) {
    require 'connection.php';

    // Получtybt вводимы[] пользователем данные обработки
    $source_date = $_POST['source_date'];
    $source_time = $_POST['source_time'];
    $dest_date = $_POST['dest_date'];
    $dest_time = $_POST['dest_time'];
    $dep_airport = $_POST['dep_airport'];
    $arr_airport = $_POST['arr_airport'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];
    $airline_name = $_POST['airline_name'];
    $flight_class = $_POST['flight_class'];
    // Выполнение необходимых проверок достоверности
    if (empty($arr_airport) || empty($dep_airport) || $airline_name == 'Select Airline') {
        setSessionMessage("Пожалуйста, заполните все обязательные для заполнения поля.");
        header('Location: add-flight.php');
        exit();
    }

    if ($dep_airport === $arr_airport) {
        setSessionMessage("Аэропорт отправления и аэропорт назначения не могут совпадать");
        header('Location: add-flight.php');
        exit();
    }

    $source_timestamp = strtotime("$source_date $source_time");
    $dest_timestamp = strtotime("$dest_date $dest_time");

    if ($source_timestamp >= $dest_timestamp) {
        setSessionMessage("Время или дата прибытия в пункт назначения должны быть больше, чем время или дата отправления");
        header('Location: add-flight.php');
        exit();
    }

    $dep_airport_id_query = "SELECT airport_id FROM airport WHERE airport_name = '$dep_airport'";
    $arr_airport_id_query = "SELECT airport_id FROM airport WHERE airport_name = '$arr_airport'";
    $airline_email_query = "SELECT email FROM airline WHERE airline_name = '$airline_name'";

    $dep_airport_id_result = mysqli_query($con, $dep_airport_id_query);
    $arr_airport_id_result = mysqli_query($con, $arr_airport_id_query);
    $airline_email_result = mysqli_query($con, $airline_email_query);

    if (!$dep_airport_id_result || !$arr_airport_id_result || !$airline_email_result) {
        // Ошибка базы данных
        header("Location: add-flight.php?error=sqlerr");
        exit();
    }

    $dep_airport_id_row = mysqli_fetch_assoc($dep_airport_id_result);
    $arr_airport_id_row = mysqli_fetch_assoc($arr_airport_id_result);
    $airline_email_row = mysqli_fetch_assoc($airline_email_result);

    $dep_airport_id = $dep_airport_id_row['airport_id'];
    $arr_airport_id = $arr_airport_id_row['airport_id'];
    $airline_email = $airline_email_row['email'];
    // Использовать пользовательские вводимые данные и полученные значения непосредственно в SQL-запросе
    $sql = "INSERT INTO flight (source_date, source_time, dest_date, dest_time, dep_airport, arr_airport, seats, price, flight_class, airline_name, dep_airport_id, arr_airport_id, airline_email) 
            VALUES ('$source_date', '$source_time', '$dest_date', '$dest_time', '$dep_airport', '$arr_airport', '$seats', '$price','$flight_class', '$airline_name', $dep_airport_id, $arr_airport_id, '$airline_email')";

    if (mysqli_query($con, $sql)) {
        setSessionMessage("Successfully inserted");
        header('Location: add-flight.php');
        exit();
    } else {
        // Ошибка базы данных
        header("Location: add-flight.php?error=sqlerr"); // Перенаправление с ошибкой
        exit();
    }
} else {
    // 
    header("Location: add-flight.php");
    exit();
}
?>

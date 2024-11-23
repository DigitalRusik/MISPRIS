<?php
session_start();
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();

if (isset($_SESSION['user_type'])) {
    include("navOptions/customer-dashboard-nav-options.php");
} else {
    include("navOptions/index-nav-options.php");
}


if (isset($_POST['source_date'])) {
    unset($_SESSION['source_date']);
}
if (isset($_POST['source_time'])) {
    unset($_SESSION['source_time']);
}
if (isset($_POST['dest_date'])) {
    unset($_SESSION['dest_date']);
}
if (isset($_POST['dest_time'])) {
    unset($_SESSION['dest_time']);
}
if (isset($_POST['dep_airport'])) {
    unset($_SESSION['dep_airport']);
}
if (isset($_POST['arr_airport'])) {
    unset($_SESSION['arr_airport']);
}
if (isset($_POST['flight_class'])) {
    unset($_SESSION['flight_class']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="css/booking-form.css" />
    <link rel="stylesheet" type="text/css" href="css/general.css">
</head>
<body>
    <header>
    </header>
    <nav>
        <a class="logo" href="index.php"> <img src="images/Easyfly.png" alt="site-logo"> </a>
        <?php include('navOptions/nav.php') ?>
    </nav>

        <footer>
            <ul>
                <li><a href="index.php">Домой</a></li>
                <li><a href="aboutUs.php">О нас</a></li>
                <li><a href="aboutUs.php#targeting-contact">Контакты</a></li>
                <li><a href="booking-form.php">Сервисы</a></li>
            </ul>
            <p>&copy 2024 МойФутбол, все права защищены</p>
        <script src="https://code.jquery.com/jquery-3.5.1.js"
            integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
            integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
            crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
            integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
            crossorigin="anonymous"></script>
    </footer>
</body>
</html>
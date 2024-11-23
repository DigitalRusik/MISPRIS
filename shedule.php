<?php
session_start();
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();

// Проверка на авторизацию
if (isset($_SESSION['user_type'])) {
    include("navOptions/customer-dashboard-nav-options.php");
} else {
    include("navOptions/index-nav-options.php");
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
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <title>Матчи</title>
</head>

<body>
    <nav>
        <a class="logo" href="index.php"> <img src="images/Easyfly.png" alt="site-logo"> </a>
        <?php include('navOptions/nav.php') ?>
    </nav>

    <div class="container mt-5">
        <h2 style="text-align: center;">Расписание матчей турнира</h2>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Стадион</th>
                    <th>Команда №1</th>
                    <th>Команда №2</th>
                    <th>Время начала матча</th>
                    <th>Время окончания матча</th>
                    <th>Места на стадионе</th>
                    <th>Цена билета</th>
                    <th>Вид билета</th>
                    <th>Информация</th>
                    <th>Результат</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("connection.php");

                // Запрос данных о матчах
                $sqlFlights = "SELECT * FROM flight";
                $resultFlights = $con->query($sqlFlights);

                // Проверка на наличие матчей и их отображение
                if ($resultFlights->num_rows > 0) {
                    while ($rowFlight = $resultFlights->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $rowFlight["airline_name"] . "</td>";
                        echo "<td>" . $rowFlight["dep_airport"] . "</td>";
                        echo "<td>" . $rowFlight["arr_airport"] . "</td>";
                        echo "<td>" . $rowFlight["source_date"] . " " . $rowFlight["source_time"] . "</td>";
                        echo "<td>" . $rowFlight["dest_date"] . " " . $rowFlight["dest_time"] . "</td>";
                        echo "<td>" . $rowFlight["seats"] . "</td>";
                        echo "<td>" . $rowFlight["price"] . "</td>";
                        echo "<td>" . $rowFlight["flight_class"] . "</td>";
                        echo "<td>" . $rowFlight["info"] . "</td>";
                        echo "<td>" . $rowFlight["result"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center'><h3>Свободных матчей нет</h3></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

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





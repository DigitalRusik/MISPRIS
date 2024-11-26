<?php
session_start();
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();

// Подключение к базе данных
include("connection.php"); // Убедитесь, что этот файл правильно настроен и подключает соединение с базой данных

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
    <title>Турнирная таблица</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        .hidden {
            display: none;
        }

        .logo-img {
            width: 50px;
            height: auto;
        }

        .rank-list {
            margin-top: 20px;
            text-align: center;
        }

        .rank-list ul {
            list-style-type: none;
            padding-left: 0;
        }

        .rank-list ul li {
            font-size: 1.2em;
            margin: 5px 0;
        }

        table {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <header></header>
    <nav>
        <a class="logo" href="index.php"> <img src="images/Easyfly.png" alt="site-logo"> </a>
        <?php include('navOptions/nav.php')?>
    </nav>

    <div class="container mt-5">
        <h2 style="text-align: center;">Турнирная таблица</h2>

        <!-- Форма поиска -->
        <div class="d-flex justify-content-center mb-4">
            <input type="text" id="search" class="form-control mr-2" style="width: 300px;" placeholder="Поиск клуба">
        </div>

        <!-- Список призеров -->
        <div class="rank-list">
            <h3>Призеры турнира</h3>
            <ul>
                <?php
                // Выбираем первые 3 клуба
                $sqlTop3 = "SELECT airport_name, place FROM airport ORDER BY place ASC LIMIT 3";
                $resultTop3 = $con->query($sqlTop3);

                if ($resultTop3->num_rows > 0) {
                    while ($row = $resultTop3->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row["place"]) . " место: " . htmlspecialchars($row["airport_name"]) . "</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <!-- Список клубов, покидающих турнир -->
        <div class="rank-list">
            <h3>Клубы, покидающие турнир</h3>
            <ul>
                <?php
                // Выбираем последние 2 клуба
                $sqlBottom2 = "SELECT airport_name, place FROM airport ORDER BY place DESC LIMIT 2";
                $resultBottom2 = $con->query($sqlBottom2);

                if ($resultBottom2->num_rows > 0) {
                    while ($row = $resultBottom2->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row["place"]) . " место: " . htmlspecialchars($row["airport_name"]) . "</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <!-- Список команд с наименьшей и наибольшей разницей мячей -->
        <div class="rank-list">
            <h3>Команды с наибольшей и наименьшей разницей мячей</h3>
            <ul>
                <?php
                // Выбираем команду с наибольшей разницей мячей
                $sqlMaxGoalDifference = "SELECT airport_name, goal_difference FROM airport ORDER BY goal_difference DESC LIMIT 1";
                $resultMaxGoalDifference = $con->query($sqlMaxGoalDifference);

                if ($resultMaxGoalDifference->num_rows > 0) {
                    $rowMax = $resultMaxGoalDifference->fetch_assoc();
                    echo "<li>Наибольшая разница мячей: " . htmlspecialchars($rowMax["airport_name"]) . " с разницей " . htmlspecialchars($rowMax["goal_difference"]) . "</li>";
                }

                // Выбираем команду с наименьшей разницей мячей
                $sqlMinGoalDifference = "SELECT airport_name, goal_difference FROM airport ORDER BY goal_difference ASC LIMIT 1";
                $resultMinGoalDifference = $con->query($sqlMinGoalDifference);

                if ($resultMinGoalDifference->num_rows > 0) {
                    $rowMin = $resultMinGoalDifference->fetch_assoc();
                    echo "<li>Наименьшая разница мячей: " . htmlspecialchars($rowMin["airport_name"]) . " с разницей " . htmlspecialchars($rowMin["goal_difference"]) . "</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Турнирная таблица -->
        <table class="table table-striped" id="airportTable">
            <thead class="table-dark">
                <tr>
                    <th>Место</th>
                    <th>Логотип</th>
                    <th>Название клуба</th>
                    <th>Игры</th>
                    <th>Победы</th>
                    <th>Поражения</th>
                    <th>Ничья</th>
                    <th>Забитые мячи</th>
                    <th>Пропущенные мячи</th>
                    <th>Разница мячей</th>
                    <th>Очки</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlAirports = "SELECT airport_id, airport_name, logo, place, game, win, defeat, draw, scored_goals, missed_goals, goal_difference, points
                                FROM airport
                                ORDER BY place ASC";
                $resultAirports = $con->query($sqlAirports);

                if ($resultAirports->num_rows > 0) {
                    while ($rowAirport = $resultAirports->fetch_assoc()) {
                        echo "<tr class='airport-row'>";
                        echo "<td>" . htmlspecialchars($rowAirport["place"]) . "</td>";
                        echo "<td><img src='uploads/" . htmlspecialchars($rowAirport["logo"]) . "' class='logo-img' alt='Логотип'></td>";
                        echo "<td>" . htmlspecialchars($rowAirport["airport_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["game"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["win"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["defeat"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["draw"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["scored_goals"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["missed_goals"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["goal_difference"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["points"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' class='text-center'>Нет данных для отображения.</td></tr>";
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
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $("#search").on("input", function() {
            var value = $(this).val().toLowerCase();
            $("#airportTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
</body>
</html>



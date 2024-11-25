<?php 
session_start();
require_once('includes/showMessage.php');
require 'includes/functions.php';
include("connection.php"); // Подключение к базе данных

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
    <title>Магазин</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        table {
            margin-left: 3%;
            margin-top: 20px;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .btn {
            border-radius: 5px;
            font-weight: 500;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-info {
            background-color: #17a2b8;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .logo-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .search-box {
            margin: 20px auto;
            width: 300px;
        }
        .search-box input {
            border-radius: 25px;
            padding: 10px;
            font-size: 16px;
        }
        .form-group label {
            font-weight: 500;
        }
        .text-muted {
            font-size: 14px;
            color: #6c757d;
        }
        .modal-dialog {
            max-width: 600px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            .search-box input {
                width: 100%;
            }
            .logo-img {
                width: 60px;
                height: 60px;
            }
            .table th, .table td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="index.php"> <img src="images/Easyfly.png" alt="site-logo"> </a>
            <?php include('navOptions/nav.php'); ?>
        </nav>
    </header>

    <div class="container">
        <h2 class="text-center text-dark">Игроки турнира</h2>

        <div class="d-flex justify-content-center search-box">
            <input type="text" id="search" class="form-control" placeholder="Поиск игрока">
        </div>

        <!-- Фильтры -->
        <div class="d-flex justify-content-between mb-4">
            <select id="ageSort" class="form-control w-25">
                <option value="">Сортировать по возрасту</option>
                <option value="asc">От наименьшего к наибольшему</option>
                <option value="desc">От наибольшего к наименьшему</option>
            </select>
            <select id="goalsSort" class="form-control w-25">
                <option value="">Сортировать по забитым мячам</option>
                <option value="asc">От наименьшего к наибольшему</option>
                <option value="desc">От наибольшего к наименьшему</option>
            </select>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>ФИО</th>
                    <th>Возраст</th>
                    <th>Клуб, номер, позиция</th>
                    <th>Желтые/Красные карточки</th>
                    <th>Забитые мячи</th>
                </tr>
            </thead>
            <tbody id="playersTable">
                <?php
                $sql = "SELECT * FROM players";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='" . htmlspecialchars($row['image']) . "' class='logo-img'></td>";
                        echo "<td>" . htmlspecialchars($row['fio']) . "</td>";
                        echo "<td class='age'>" . (int)$row['age'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['num_pos']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cards']) . "</td>";
                        echo "<td class='goals'>" . (int)$row['goals'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-muted'>Нет данных</td></tr>";
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

    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>

    <script>
        // Поиск по таблице
        document.getElementById('search').addEventListener('input', function (event) {
            let searchTerm = event.target.value.toLowerCase();
            let tableRows = document.querySelectorAll('#playersTable tr');
            tableRows.forEach(function (row) {
                let cells = row.getElementsByTagName('td');
                let match = false;
                Array.from(cells).forEach(function (cell) {
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                    }
                });
                row.style.display = match ? '' : 'none';
            });
        });

        // Сортировка по возрасту
        document.getElementById('ageSort').addEventListener('change', function () {
            let order = this.value;
            sortTable('age', order);
        });

        // Сортировка по забитым мячам
        document.getElementById('goalsSort').addEventListener('change', function () {
            let order = this.value;
            sortTable('goals', order);
        });

        function sortTable(className, order) {
            let rows = Array.from(document.querySelectorAll('#playersTable tr'));
            rows.sort(function (a, b) {
                let aValue = parseInt(a.querySelector('.' + className).textContent);
                let bValue = parseInt(b.querySelector('.' + className).textContent);
                if (order === 'asc') return aValue - bValue;
                if (order === 'desc') return bValue - aValue;
                return 0;
            });

            let tbody = document.querySelector('#playersTable');
            rows.forEach(row => tbody.appendChild(row));
        }
    </script>
</body>
</html>
<?php 
session_start();
require 'includes/functions.php';
include("connection.php");
require_once('includes/showMessage.php');
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
    <title>Магазин</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/general.css">
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
    <nav>
        <a class="logo" href="index.php"><img src="images/Easyfly.png" alt="site-logo"></a>
        <?php include('navOptions/nav.php'); ?>
    </nav>

    <div class="container mt-5">
        <h2 style="text-align: center;">Каталог товаров</h2>

        <!-- Search -->
        <div class="d-flex justify-content-center mb-4">
            <input type="text" id="search" class="form-control w-50" placeholder="Поиск товара">
        </div>

        <!-- Items Table -->
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Описание</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from database
                $sql = "SELECT * FROM shop";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='uploads/" . htmlspecialchars($row['image']) . "' class='logo-img' alt='item-image'></td>";
                        echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['price']) . " ₽</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'><h3>Товаров пока нет</h3></td></tr>";
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
    <script>
        // Search Filter
        $('#search').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
</body>
</html>
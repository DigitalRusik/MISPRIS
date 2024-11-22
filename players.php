<?php require_once('includes/showMessage.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
    exit();
}

require 'includes/functions.php';
include("connection.php");



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .modal-footer .btn {
            width: 100%;
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

        .modal-body {
            padding: 30px;
        }
        
        .text-muted {
            font-size: 14px;
            color: #6c757d;
        }

        .modal-dialog {
            max-width: 600px;
        }

        /* Responsiveness for smaller devices */
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
    <?php include('includes/admin-nav.php'); ?>

    <div class="container">
        <h2 class="text-center text-dark">Управление игроками</h2>

        <!-- Search -->
        <div class="d-flex justify-content-center search-box">
            <input type="text" id="search" class="form-control" placeholder="Поиск товара">
        </div>

        <!-- Add Item Button -->
        <div class="d-flex justify-content-center mb-4">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addItemModal">Добавить игрока</button>
        </div>

        <!-- Items Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>ФИО</th>
                    <th>Возраст</th>
                    <th>Желтые и Красные карточки</th>
                    <th>Забитые/пропущенные мячи</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>

        

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>     
</body>

</html>
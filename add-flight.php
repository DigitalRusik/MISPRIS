<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();


require 'connection.php';

if (isset($_POST['flight_but'])) {
    // Извлечение данных формы
    $source_date = $_POST['source_date'];
    $source_time = $_POST['source_time'];
    $dest_date = $_POST['dest_date'];
    $dest_time = $_POST['dest_time'];
    $dep_airport = $_POST['dep_airport'];
    $arr_airport = $_POST['arr_airport'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];
    $flight_class = $_POST['flight_class']; // Добавлен класс полета
    $airline_name = $_POST['airline_name']; // Возможно, вам также потребуется получить это значение

    // Выполнить вставку базы данных
    $sql = "INSERT INTO flight (source_date, source_time, dest_date, dest_time, dep_airport, arr_airport, seats, price, flight_class, airline_name, dep_airport_id, arr_airport_id, airline_email)
            VALUES ('$source_date', '$source_time', '$dest_date', '$dest_time', '$dep_airport', '$arr_airport', $seats, $price, '$flight_class', '$airline_name', $dep_airport_id, $arr_airport_id, '$airline_email')";

    if (mysqli_query($con, $sql)) {
        // Запись о полете была успешно добавлена
        // Вы можете добавить сообщение об успешном завершении или перенаправить на другую страницу
    } else {
        // Обработка ошибок при добавлении в базу данных
        echo 'Error: ' . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require 'connection.php'; ?>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="form.css">

    <title>Добавить полет</title>
</head>

<body>
<style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            color: #333;
        }

        input[type="date"],
        input[type="time"],
        input[type="number"],
        select {
            border: none;
            border-bottom: 2px solid #5c5c5c;
            border-radius: 0;
            font-weight: bold;
            background-color: #f5f5f5;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }

        .form-row {
            margin-bottom: 70px;
        }

        .btn-success {
            background-color: #4CAF50;
            border: none;
            padding: 10px 30px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            transition: background-color 0.3s;
        }

        .btn-success:hover {
            background-color: #45a049;
        }
    </style>

    <?php include('includes/admin-nav.php'); ?>

    <div class="container mt-0">
        <div class="row">
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] === 'destless') {
                    echo "<script>alert('Dest. date/time is less than src.');</script>";
                } else if ($_GET['error'] === 'sqlerr') {
                    echo "<script>alert('Database error');</script>";
                } else if ($_GET['error'] === 'same') {
                    echo "<script>alert('Same city specified in source and destination');</script>";
                }
            }
            ?>
            <div class="bg-light form-out col-md-12">
                <h3 class="text-secondary text-center">ДОБАВИТЬ ИНФОРМАЦИЮ О РЕЙСЕ</h3>
 
                <form method="POST" class="text-center" action="flightinc.php" style="margin-left: 10%;">

                    <div class="form-row mb-4">
                        <div class="col-md-3 p-0">
                            <h5 class="mb-0 form-name">Отправление</h5>
                        </div>
                        <div class="col">
                            <input type="date" name="source_date" class="form-control" required>
                        </div>
                        <div class="col">
                            <input type="time" name="source_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row mb-4">
                        <div class="col-md-3 ">
                            <h5 class="form-name mb-0">Место прибытия</h5>
                        </div>
                        <div class="col">
                            <input type="date" name="dest_date" class="form-control" required>
                        </div>
                        <div class="col">
                            <input type="time" name="dest_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row mb-4">
                        <div class="col">
                            <?php
                            // Создание SQL-запроса для выбора названий всех аэропортов
                            $sql = 'SELECT airport_name FROM airport';
                            // Выполнение SQL-запроса
                            $result = mysqli_query($con, $sql);

                            // Проверка, был ли запрос выполнен успешно
                            if ($result) {
                                echo '<select class="mt-4" name="dep_airport" style="border: 0px; border-bottom: 
                        2px solid #5c5c5c; background-color: whitesmoke !important;
                        font-weight: bold !important;
                        width:80%" required>
                        <option value="" disabled selected>Команда 1</option>';

                                // Просмотр результатов и создание вариантов для каждого аэропорта
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['airport_name'] . '">' . $row['airport_name'] . '</option>';
                                }

                                echo '</select>';
                            } else {
                                // Обработка случая, когда запрос завершается ошибкой
                                echo 'Error: Unable to fetch airport data.';
                            }
                            ?>
                        </div>
                        <div class="col">
                            <?php
                            // Создание SQL-запроса для выбора названий всех аэропортов
                            $sql = 'SELECT airport_name FROM airport';

                            // Выполнение SQL-запрос
                            $result = mysqli_query($con, $sql);

                            // Проверка, был ли запрос выполнен успешно
                            if ($result) {
                                echo '<select class="mt-4" name="arr_airport" style="border: 0px; border-bottom: 
                        2px solid #5c5c5c; background-color: whitesmoke !important;
                        font-weight: bold !important;
                        width:80%" required>
                        <option value="" disabled selected> Команда 2 </option>';

                                // Просмотр результатов и создание варианта для каждого аэропорта
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['airport_name'] . '">' . $row['airport_name'] . '</option>';
                                }

                                echo '</select>';
                            } else {
                                // Обработка случая, когда запрос завершается ошибкой
                                echo 'Error: Unable to fetch airport data.';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="input-group">
                                <label for="dura">Места:</label>
                                <input type="number" name="seats" id="dura" required />
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label for="price">Цена:</label>
                                <input type="number" style="border: 0px; border-bottom: 2px solid #5c5c5c;" name="price" id="price"
                                    required />
                            </div>
                        </div>
                        <div class="col">
    <select class="form-control" name="flight_class" required>
        <option value="" disabled selected>Выберите класс:</option>
        <option value="Economy">Обычный</option>
        <option value="Business">VIP</option>
        <option value="First Class">VIP+++</option>
    </select>
</div>

                        <div class="col">
                            <?php
                            // Создание SQL-запроса для выбора названий всех авиакомпаний
                            $sql = 'SELECT airline_name FROM airline';
                            // Выполнение SQL-запроса
                            $result = mysqli_query($con, $sql);

                            // Проверка, был ли запрос выполнен успешно
                            if ($result) {
                                echo '<select class="airline col-md-3 mt-4" name="airline_name" style="border: 0px; border-bottom: 
                              2px solid #5c5c5c; background-color: whitesmoke !important;" required>
                              <option value="" disabled selected>Выберите авиакомпанию</option>';

                                // Просмотр результатов и создание варианта для каждой авиакомпании
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['airline_name'] . '">' . $row['airline_name'] . '</option>';
                                }

                                echo '</select>';
                            } else {
                                // Обработка случая, когда запрос завершается ошибкой
                                echo 'Error: Unable to fetch airline data.';
                            }
                            ?>
                        </div>
                    </div>
                    <button name="flight_but" type="submit" class="btn btn-success mt-5">
                        <div style="font-size: 1.5rem;">
                            <i class="fa fa-lg fa-arrow-right"></i>Подтвердить
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
      $('.input-group input').focus(function () {
        me = $(this);
        $("label[for='" + me.attr('id') + "']").addClass("animate-label");
      });
      $('.input-group input').blur(function () {
        me = $(this);
        if (me.val() == "") {
          $("label[for='" + me.attr('id') + "']").removeClass("animate-label");
        }
      });
    });
  </script>

</body>

</html>

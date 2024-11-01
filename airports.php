<?php require_once('includes/showMessage.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
    exit();
}
require 'includes/functions.php';
include("connection.php");

// Обработка добавления аэропорта
if (isset($_POST['add_airport'])) {
    $airportName = $_POST['airport_name'];
    $capacity = $_POST['capacity'];
    $city = $_POST['city'];  // Получаем город из формы

    $insertSql = "INSERT INTO airport (airport_name, capacity, city) VALUES (?, ?, ?)";
    $stmt = $con->prepare($insertSql);
    $stmt->bind_param("sis", $airportName, $capacity, $city);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Аэропорт успешно добавлен!";
    } else {
        $_SESSION['message'] = "Ошибка при добавлении аэропорта.";
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка удаления аэропорта
if (isset($_POST['confirm_delete_airport'])) {
    $airportId = $_POST['delete_airport_id'];
    $deleteSql = "DELETE FROM airport WHERE airport_id = ?";
    $stmt = $con->prepare($deleteSql);
    $stmt->bind_param("i", $airportId);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Аэропорт успешно удален!";
    } else {
        $_SESSION['message'] = "Ошибка при удалении аэропорта.";
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

displaySessionMessage();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Добавление аэропортов</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            margin-left: 10%;
            margin-top: 20px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
<?php include('includes/admin-nav.php'); ?>
    <div class="container mt-5">
        <h2 style="text-align: center;">Аэропорты (Клубы)</h2>

        <!-- Форма поиска -->
        <div class="d-flex justify-content-center mb-4">
            <input type="text" id="search" class="form-control mr-2" style="width: 300px;" placeholder="Поиск аэропорта">
        </div>

        <!-- Кнопка добавления аэропорта -->
        <div class="d-flex justify-content-center mb-4">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAirportModal">Добавить аэропорт</button>
        </div>

        <table class="table table-striped" id="airportTable">
            <thead class="table-dark">
                <tr>
                    <th>Название аэропорта</th>
                    <th>Город</th>
                    <th>Вместимость</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SQL-запрос для получения всех аэропортов
                $sqlAirports = "SELECT * FROM airport";
                $resultAirports = $con->query($sqlAirports);

                if ($resultAirports->num_rows > 0) {
                    while ($rowAirport = $resultAirports->fetch_assoc()) {
                        echo "<tr class='airport-row'>";
                        echo "<td>" . htmlspecialchars($rowAirport["airport_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["city"]) . "</td>";  // Вывод города
                        echo "<td>" . htmlspecialchars($rowAirport["capacity"]) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-danger btn-sm delete-airport' data-id='" . $rowAirport["airport_id"] . "' data-toggle='modal' data-target='#deleteAirportModal'>Удалить</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'><h3>Нет доступных аэропортов</h3></td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Модальное окно добавления аэропорта -->
        <div class="modal fade" id="addAirportModal" tabindex="-1" role="dialog" aria-labelledby="addAirportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAirportModalLabel">Добавить аэропорт</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="airport_name">Название аэропорта</label>
                                <input type="text" class="form-control" id="airport_name" name="airport_name" required>
                            </div>
                            <div class="form-group">
                                <label for="city">Город</label>
                                <input type="text" class="form-control" id="city" name="city" required> <!-- Поле для города -->
                            </div>
                            <div class="form-group">
                                <label for="capacity">Вместимость</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                                <button type="submit" class="btn btn-primary" name="add_airport">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Удаление аэропорта -->
        <form action="" method="POST">
            <input type="hidden" name="delete_airport_id" id="delete_airport_id" value="">
            <div class="modal fade" id="deleteAirportModal" tabindex="-1" role="dialog" aria-labelledby="deleteAirportModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteAirportModalLabel">Подтвердите удаление</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Вы уверены, что хотите удалить этот аэропорт?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-danger" name="confirm_delete_airport">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap and jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
        // Фильтрация аэропортов при вводе в поле поиска
        $('#search').on('input', function() {
            var searchTerm = $(this).val().toLowerCase().trim();
            $('#airportTable tbody tr.airport-row').each(function() {
                var airportName = $(this).find('td:first').text().toLowerCase();
                if (airportName.includes(searchTerm)) {
                    $(this).removeClass('hidden'); // Показываем строку
                } else {
                    $(this).addClass('hidden'); // Скрываем строку
                }
            });
        });

        $(document).on("click", ".delete-airport", function () {
            var deleteAirportId = $(this).data('id');
            $('#delete_airport_id').val(deleteAirportId);
        });
    </script>
</body>

</html>

<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Матчи</title>
    <link rel="stylesheet" href="css/style.css" />
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<body>
    <?php include('includes/admin-nav.php'); ?>

    <div class="container mt-5">
        <h2 style="text-align: center;">Список матчей</h2>
      
        <style>
        table {
            margin-left: 5%; /* Сдвигаем таблицу вправо */
        }
        </style>

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
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("connection.php");

                // Удаление матча
                if (isset($_POST["confirm_delete_flight"])) {
                    $deleteFlightId = $_POST["delete_flight_id"];
                    $deleteSql = "DELETE FROM flight WHERE id = '$deleteFlightId'";
                    if ($con->query($deleteSql) === TRUE) {
                        setSessionMessage("Матч успешно удален");
                        header('location: show-flight.php');
                    } else {
                        echo "<script>showModal('errorModal', 'Ошибка при удалении: " . $con->error . "');</script>";
                    }
                }

                // Обновление матча
                if (isset($_POST["confirm_edit_flight"])) {
                    $editFlightId = $_POST["edit_flight_id"];
                    $source_time = $_POST["edit_source_time"];
                    $dest_time = $_POST["edit_dest_time"];
                    $info = $_POST["edit_info"];
                    $result = $_POST["edit_result"];

                    $updateSql = "UPDATE flight 
                                  SET source_time = '$source_time', 
                                      dest_time = '$dest_time', 
                                      info = '$info', 
                                      result = '$result' 
                                  WHERE id = '$editFlightId'";
                    if ($con->query($updateSql) === TRUE) {
                        setSessionMessage("Информация о матче успешно обновлена");
                        header('location: show-flight.php');
                    } else {
                        echo "<script>showModal('errorModal', 'Ошибка при обновлении: " . $con->error . "');</script>";
                    }
                }

                // Отображение матчей в таблице
                $sqlFlights = "SELECT * FROM flight";
                $resultFlights = $con->query($sqlFlights);

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
                        echo "<td>";
                        echo "<button class='btn btn-primary btn-sm edit-flight' data-id='" . $rowFlight["id"] . "' data-source_time='" . $rowFlight["source_time"] . "' data-dest_time='" . $rowFlight["dest_time"] . "' data-info='" . $rowFlight["info"] . "' data-result='" . $rowFlight["result"] . "' data-toggle='modal' data-target='#editFlightModal'>Редактировать</button>";
                        echo " ";
                        echo "<button class='btn btn-danger btn-sm delete-flight' data-id='" . $rowFlight["id"] . "' data-toggle='modal' data-target='#deleteFlightModal'>Удалить</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center'><h3>Свободных матчей нет</h3></td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Удаление матча -->
        <form action="" method="POST">
            <input type="hidden" name="delete_flight_id" id="delete_flight_id" value="">
            <div class="modal fade" id="deleteFlightModal" tabindex="-1" role="dialog"
                aria-labelledby="deleteFlightModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteFlightModalLabel">Подтвердите удаление</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Вы уверены, что хотите удалить этот матч?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                            <button type="submit" class="btn btn-danger" name="confirm_delete_flight">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Редактирование матча -->
        <form action="" method="POST">
            <input type="hidden" name="edit_flight_id" id="edit_flight_id" value="">
            <div class="modal fade" id="editFlightModal" tabindex="-1" role="dialog"
                aria-labelledby="editFlightModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editFlightModalLabel">Редактировать информацию о матче</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="edit_source_time">Время начала матча</label>
                                <input type="time" name="edit_source_time" id="edit_source_time" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_dest_time">Время окончания матча</label>
                                <input type="time" name="edit_dest_time" id="edit_dest_time" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_info">Информация</label>
                                <textarea name="edit_info" id="edit_info" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_result">Результат</label>
                                <input type="text" name="edit_result" id="edit_result" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                            <button type="submit" class="btn btn-primary" name="confirm_edit_flight">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap and jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript to handle modals -->
    <script>
        // Нажатие обработчика событий для кнопок редактирования
        $(document).on("click", ".edit-flight", function () {
            var editFlightId = $(this).data('id');
            var sourceTime = $(this).data('source_time');
            var destTime = $(this).data('dest_time');
            var info = $(this).data('info');
            var result = $(this).data('result');

            $('#edit_flight_id').val(editFlightId);
            $('#edit_source_time').val(sourceTime);
            $('#edit_dest_time').val(destTime);
            $('#edit_info').val(info);
            $('#edit_result').val(result);
        });

        // Нажатие обработчика событий для кнопок удаления
        $(document).on("click", ".delete-flight", function () {
            var deleteFlightId = $(this).data('id');
            $('#delete_flight_id').val(deleteFlightId);
        });
    </script>
</body>

</html>

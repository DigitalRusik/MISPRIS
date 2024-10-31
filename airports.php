<?php require_once('includes/showMessage.php') ?>
<?php
session_start();
if(!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require 'includes/functions.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<body>
<?php include('includes/admin-nav.php'); ?>
    <div class="container mt-5">
        <h2 style="text-align: center;">Аэропорты</h2>

        <!-- Форма поиска -->
        <div class="d-flex justify-content-center mb-4">
            <input type="text" id="search" class="form-control mr-2" style="width: 300px;" placeholder="Поиск аэропорта" 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>

        <!-- Кнопка добавления аэропорта -->
        <div class="d-flex justify-content-center mb-4">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAirportModal">Добавить аэропорт</button>
        </div>

        <style>
        table {
            margin-left: 10%; /* Сдвигаем таблицу вправо */
            margin-top: 20px; /* Добавляем отступ сверху для таблицы */
        }
        .hidden {
            display: none; /* Скрываем строки */
        }
        </style>
        
        <table class="table table-striped" id="airportTable">
            <thead class="table-dark">
                <tr>
                    <th>Название аэропорта</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("connection.php");

                // SQL-запрос для получения всех аэропортов
                $sqlAirports = "SELECT * FROM airport";
                $resultAirports = $con->query($sqlAirports);

                if ($resultAirports->num_rows > 0) {
                    while ($rowAirport = $resultAirports->fetch_assoc()) {
                        echo "<tr class='airport-row'>";
                        echo "<td>" . htmlspecialchars($rowAirport["airport_name"]) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-danger btn-sm delete-airport' data-id='" . $rowAirport["airport_id"] . "' data-toggle='modal' data-target='#deleteAirportModal'>Удалить</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2' class='text-center'><h3>Нет доступных аэропортов</h3></td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Добавление аэропорта -->
        <div class="modal fade" id="addAirportModal" tabindex="-1" role="dialog" aria-labelledby="addAirportModalLabel"
            aria-hidden="true">
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
            <div class="modal fade" id="deleteAirportModal" tabindex="-1" role="dialog"
                aria-labelledby="deleteAirportModalLabel" aria-hidden="true">
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Фильтрация аэропортов при вводе в поле поиска
        $('#search').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('#airportTable tbody tr.airport-row').each(function() {
                var airportName = $(this).find('td:first').text().toLowerCase();
                if (airportName.indexOf(searchTerm) > -1) {
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

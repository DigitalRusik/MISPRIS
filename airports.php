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
    $city = $_POST['city'];
    $prevPlace = $_POST['prev_place']; // новое поле
    $logo = ''; // Инициализация переменной логотипа

    // Обработка загрузки файла
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logoTmpName = $_FILES['logo']['tmp_name'];
        $logoName = basename($_FILES['logo']['name']);
        $uploadDir = 'uploads/';
        $logoPath = $uploadDir . $logoName;

        // Перемещение загруженного файла в каталог
        if (move_uploaded_file($logoTmpName, $logoPath)) {
            $logo = $logoName; // Сохраняем имя файла
        }
    }

    $insertSql = "INSERT INTO airport (airport_name, capacity, city, logo, prev_place) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($insertSql);
    $stmt->bind_param("sssss", $airportName, $capacity, $city, $logo, $prevPlace); // Привязываем все поля
    if ($stmt->execute()) {
        $_SESSION['message'] = "Клуб успешно добавлен!";
    } else {
        $_SESSION['message'] = "Ошибка при добавлении клуба.";
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка удаления аэропорта
if (isset($_POST['confirm_delete_airport'])) {
    $airportId = $_POST['delete_airport_id'];

    // Получаем имя логотипа перед удалением
    $selectSql = "SELECT logo FROM airport WHERE airport_id = ?";
    $stmt = $con->prepare($selectSql);
    $stmt->bind_param("i", $airportId);
    $stmt->execute();
    $stmt->bind_result($logoName);
    $stmt->fetch();
    $stmt->close();

    $deleteSql = "DELETE FROM airport WHERE airport_id = ?";
    $stmt = $con->prepare($deleteSql);
    $stmt->bind_param("i", $airportId);
    if ($stmt->execute()) {
        // Удаляем файл логотипа, если он существует
        if ($logoName) {
            unlink("uploads/" . $logoName);
        }
        $_SESSION['message'] = "Клуб успешно удален!";
    } else {
        $_SESSION['message'] = "Ошибка при удалении клуба.";
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка редактирования аэропорта
if (isset($_POST['edit_airport'])) {
    $airportId = $_POST['airport_id'];
    $airportName = $_POST['edit_airport_name'];
    $capacity = $_POST['edit_capacity'];
    $city = $_POST['edit_city'];
    $prevPlace = $_POST['edit_prev_place']; // новое поле
    $logo = ''; // Инициализация переменной логотипа

    // Обработка загрузки файла
    if (isset($_FILES['edit_logo']) && $_FILES['edit_logo']['error'] === UPLOAD_ERR_OK) {
        $logoTmpName = $_FILES['edit_logo']['tmp_name'];
        $logoName = basename($_FILES['edit_logo']['name']);
        $uploadDir = 'uploads/';
        $logoPath = $uploadDir . $logoName;

        // Перемещение загруженного файла в каталог
        if (move_uploaded_file($logoTmpName, $logoPath)) {
            $logo = $logoName;
        }
    }

    // Обновление данных с логотипом, если он был изменен
    if ($logo) {
        $updateSql = "UPDATE airport SET airport_name = ?, capacity = ?, city = ?, logo = ?, prev_place = ? WHERE airport_id = ?";
        $stmt = $con->prepare($updateSql);
        $stmt->bind_param("sssssi", $airportName, $capacity, $city, $logo, $prevPlace, $airportId);
    } else {
        // Если логотип не был изменен, обновляем без него
        $updateSql = "UPDATE airport SET airport_name = ?, capacity = ?, city = ?, prev_place = ? WHERE airport_id = ?";
        $stmt = $con->prepare($updateSql);
        $stmt->bind_param("ssssi", $airportName, $capacity, $city, $prevPlace, $airportId);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Клуб успешно обновлен!";
    } else {
        $_SESSION['message'] = "Ошибка при обновлении клуба.";
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
    <title>Клубы</title>
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

        .logo-img {
            width: 50px;
            height: auto;
        }
    </style>
</head>

<body>
    <?php include('includes/admin-nav.php'); ?>
    <div class="container mt-5">
        <h2 style="text-align: center;">Клубы</h2>

        <!-- Форма поиска -->
        <div class="d-flex justify-content-center mb-4">
            <input type="text" id="search" class="form-control mr-2" style="width: 300px;" placeholder="Поиск клуба">
        </div>

        <!-- Кнопка добавления аэропорта -->
        <div class="d-flex justify-content-center mb-4">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAirportModal">Добавить клуб</button>
        </div>

        <table class="table table-striped" id="airportTable">
            <thead class="table-dark">
                <tr>
                    <th>Логотип</th>
                    <th>Название клуба</th>
                    <th>Город базирования</th>
                    <th>Главный тренер</th>
                    <th>Место в прошлом сезоне</th>
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
                        echo "<td><img src='uploads/" . htmlspecialchars($rowAirport["logo"]) . "' class='logo-img' alt='Логотип'></td>";
                        echo "<td>" . htmlspecialchars($rowAirport["airport_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["city"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["capacity"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["prev_place"]) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-info btn-sm edit-airport' data-id='" . $rowAirport["airport_id"] . "' data-name='" . htmlspecialchars($rowAirport["airport_name"]) . "' data-city='" . htmlspecialchars($rowAirport["city"]) . "' data-capacity='" . htmlspecialchars($rowAirport["capacity"]) . "' data-prev-place='" . htmlspecialchars($rowAirport["prev_place"]) . "' data-logo='" . htmlspecialchars($rowAirport["logo"]) . "' data-toggle='modal' data-target='#editAirportModal'>Редактировать</button>";
                        echo "<button class='btn btn-danger btn-sm delete-airport' data-id='" . $rowAirport["airport_id"] . "' data-toggle='modal' data-target='#deleteAirportModal'>Удалить</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Нет данных для отображения.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Add Airport Modal -->
        <div class="modal fade" id="addAirportModal" tabindex="-1" role="dialog" aria-labelledby="addAirportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAirportModalLabel">Добавить клуб</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="airport_name">Название клуба:</label>
                                <input type="text" class="form-control" name="airport_name" required>
                            </div>
                            <div class="form-group">
                                <label for="capacity">Город базирования:</label>
                                <input type="text" class="form-control" name="city" required>
                            </div>
                            <div class="form-group">
                                <label for="capacity">Вместимость:</label>
                                <input type="text" class="form-control" name="capacity" required>
                            </div>
                            <div class="form-group">
                                <label for="prev_place">Место в прошлом сезоне:</label>
                                <input type="text" class="form-control" name="prev_place" required>
                            </div>
                            <div class="form-group">
                                <label for="logo">Логотип клуба:</label>
                                <input type="file" class="form-control-file" name="logo">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" name="add_airport" class="btn btn-primary">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Airport Modal -->
        <div class="modal fade" id="editAirportModal" tabindex="-1" role="dialog" aria-labelledby="editAirportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAirportModalLabel">Редактировать клуб</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="airport_id" id="edit_airport_id">
                            <div class="form-group">
                                <label for="edit_airport_name">Название клуба:</label>
                                <input type="text" class="form-control" name="edit_airport_name" id="edit_airport_name" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_capacity">Город базирования:</label>
                                <input type="text" class="form-control" name="edit_city" id="edit_city" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_capacity">Вместимость:</label>
                                <input type="text" class="form-control" name="edit_capacity" id="edit_capacity" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_prev_place">Место в прошлом сезоне:</label>
                                <input type="text" class="form-control" name="edit_prev_place" id="edit_prev_place" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_logo">Логотип клуба:</label>
                                <input type="file" class="form-control-file" name="edit_logo">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" name="edit_airport" class="btn btn-primary">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Airport Modal -->
        <div class="modal fade" id="deleteAirportModal" tabindex="-1" role="dialog" aria-labelledby="deleteAirportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteAirportModalLabel">Удалить клуб</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="delete_airport_id" id="delete_airport_id">
                            <p>Вы уверены, что хотите удалить этот клуб?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            <button type="submit" name="confirm_delete_airport" class="btn btn-danger">Удалить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Обработка редактирования данных в модальном окне
        $(document).on('click', '.edit-airport', function () {
            $('#edit_airport_id').val($(this).data('id'));
            $('#edit_airport_name').val($(this).data('name'));
            $('#edit_capacity').val($(this).data('capacity'));
            $('#edit_city').val($(this).data('city'));
            $('#edit_prev_place').val($(this).data('prev-place'));
        });

        // Обработка удаления аэропорта
        $(document).on('click', '.delete-airport', function () {
            $('#delete_airport_id').val($(this).data('id'));
        });
    </script>
</body>
</html>
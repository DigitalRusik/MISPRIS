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
    $logo = ''; // Initialize logo variable

    // Handle file upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logoTmpName = $_FILES['logo']['tmp_name'];
        $logoName = basename($_FILES['logo']['name']);
        $uploadDir = 'uploads/'; // Ensure this directory exists and is writable
        $logoPath = $uploadDir . $logoName;

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($logoTmpName, $logoPath)) {
            $logo = $logoName; // Store the name in the variable
        }
    }

    $insertSql = "INSERT INTO airport (airport_name, capacity, city, logo) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($insertSql);
    $stmt->bind_param("siss", $airportName, $capacity, $city, $logo);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Клуб успешно добавлен!";
    } else {
        $_SESSION['message'] = "Ошибка при добавлении клуба.";
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка удаления клуба
if (isset($_POST['confirm_delete_airport'])) {
    $airportId = $_POST['delete_airport_id'];

    // Get the logo name before deletion
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
        // Remove logo file if exists
        if ($logoName) {
            unlink("uploads/" . $logoName); // Delete the logo file
        }
        $_SESSION['message'] = "Клуб успешно удален!";
    } else {
        $_SESSION['message'] = "Ошибка при удалении клуб.";
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка редактирования клуба
if (isset($_POST['edit_airport'])) {
    $airportId = $_POST['airport_id'];
    $airportName = $_POST['edit_airport_name'];
    $capacity = $_POST['edit_capacity'];
    $city = $_POST['edit_city'];
    $logo = ''; // Initialize logo variable

    // Handle file upload
    if (isset($_FILES['edit_logo']) && $_FILES['edit_logo']['error'] === UPLOAD_ERR_OK) {
        $logoTmpName = $_FILES['edit_logo']['tmp_name'];
        $logoName = basename($_FILES['edit_logo']['name']);
        $uploadDir = 'uploads/'; // Ensure this directory exists and is writable
        $logoPath = $uploadDir . $logoName;

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($logoTmpName, $logoPath)) {
            $logo = $logoName; // Store the name in the variable
        }
    }

    // Update SQL with logo if provided
    if ($logo) {
        $updateSql = "UPDATE airport SET airport_name = ?, capacity = ?, city = ?, logo = ? WHERE airport_id = ?";
        $stmt = $con->prepare($updateSql);
        $stmt->bind_param("sisii", $airportName, $capacity, $city, $logo, $airportId);
    } else {
        // If no new logo is uploaded, update without changing the logo
        $updateSql = "UPDATE airport SET airport_name = ?, capacity = ?, city = ? WHERE airport_id = ?";
        $stmt = $con->prepare($updateSql);
        $stmt->bind_param("siii", $airportName, $capacity, $city, $airportId);
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
            width: 50px; /* Adjust size as needed */
            height: auto;
        }
    </style>
</head>

<body>
<?php include('includes/admin-nav.php'); ?>
    <div class="container mt-5">
        <h2 style="text-align: center;">Турнирная таблица</h2>

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
                    <th>Город</th>
                    <th>Вместимость стадиона</th>
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
                        echo "<td>";
                        echo "<button class='btn btn-info btn-sm edit-airport' data-id='" . $rowAirport["airport_id"] . "' data-name='" . htmlspecialchars($rowAirport["airport_name"]) . "' data-city='" . htmlspecialchars($rowAirport["city"]) . "' data-capacity='" . htmlspecialchars($rowAirport["capacity"]) . "' data-logo='" . htmlspecialchars($rowAirport["logo"]) . "' data-toggle='modal' data-target='#editAirportModal'>Редактировать</button>";
                        echo "<button class='btn btn-danger btn-sm delete-airport' data-id='" . $rowAirport["airport_id"] . "' data-toggle='modal' data-target='#deleteAirportModal'>Удалить</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Нет данных для отображения.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Add Airport Modal -->
        <div class="modal fade" id="addAirportModal" tabindex="-1" role="dialog" aria-labelledby="addAirportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAirportModalLabel">Добавление клуба</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="airport_name">Название клуба:</label>
                                <input type="text" class="form-control" name="airport_name" required>
                            </div>
                            <div class="form-group">
                                <label for="capacity">Вместимость:</label>
                                <input type="number" class="form-control" name="capacity" required>
                            </div>
                            <div class="form-group">
                                <label for="city">Город:</label>
                                <input type="text" class="form-control" name="city" required>
                            </div>
                            <div class="form-group">
                                <label for="logo">Логотип:</label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary" name="add_airport">Добавить клуб</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Airport Modal -->
        <div class="modal fade" id="editAirportModal" tabindex="-1" role="dialog" aria-labelledby="editAirportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAirportModalLabel">Редактирование клуба</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="airport_id" id="edit_airport_id">
                            <div class="form-group">
                                <label for="edit_airport_name">Название клуба:</label>
                                <input type="text" class="form-control" name="edit_airport_name" id="edit_airport_name" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_capacity">Вместимость:</label>
                                <input type="number" class="form-control" name="edit_capacity" id="edit_capacity" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_city">Город:</label>
                                <input type="text" class="form-control" name="edit_city" id="edit_city" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_logo">Логотип:</label>
                                <input type="file" class="form-control" name="edit_logo" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary" name="edit_airport">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Airport Modal -->
        <div class="modal fade" id="deleteAirportModal" tabindex="-1" role="dialog" aria-labelledby="deleteAirportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAirportModalLabel">Удаление клуба</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="delete_airport_id" id="delete_airport_id">
                            <p>Вы уверены, что хотите удалить этот клуб?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Нет</button>
                            <button type="submit" class="btn btn-danger" name="confirm_delete_airport">Да, удалить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Populate the edit modal with the current airport data
        $(document).on('click', '.edit-airport', function () {
            $('#edit_airport_id').val($(this).data('id'));
            $('#edit_airport_name').val($(this).data('name'));
            $('#edit_capacity').val($(this).data('capacity'));
            $('#edit_city').val($(this).data('city'));
        });

        // Populate the delete modal with the airport id
        $(document).on('click', '.delete-airport', function () {
            $('#delete_airport_id').val($(this).data('id'));
        });

        // Search functionality
        $('#search').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#airportTable .airport-row').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
</body>
</html>
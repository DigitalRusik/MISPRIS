<?php 
session_start();
require_once('includes/showMessage.php');
require 'includes/functions.php';
include("connection.php"); // Подключение к базе данных

// Проверка на авторизацию
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
    exit();
}

// Обработка удаления игрока
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM players WHERE id = $id";
    if ($con->query($delete_sql)) {
        header('Location: players.php');
    } else {
        echo "Ошибка при удалении игрока.";
    }
}

// Обработка редактирования игрока
if (isset($_POST['edit_player'])) {
    $id = (int)$_POST['id'];
    $fio = $_POST['fio'];
    $age = (int)$_POST['age'];
    $num_pos = $_POST['num_pos'];
    $cards = $_POST['cards'];
    $goals = (int)$_POST['goals'];

    // Обработка загрузки изображения
    $image = $_FILES['image']['name'];
    if ($image) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = 'uploads/' . $image;
        move_uploaded_file($image_tmp, $image_path);
    } else {
        $image_path = $_POST['current_image']; // если изображение не изменилось
    }

    // Обновление данных в базе
    $update_sql = "UPDATE players SET fio = ?, age = ?, num_pos = ?, cards = ?, goals = ?, image = ? WHERE id = ?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("sissssi", $fio, $age, $num_pos, $cards, $goals, $image_path, $id);
    if ($stmt->execute()) {
        header('Location: players.php');
    } else {
        echo "Ошибка при обновлении данных игрока.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Участники турнира</title>
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
            <input type="text" id="search" class="form-control" placeholder="Поиск игрока">
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
                    <th>Номер, позиция</th>
                    <th>Желтые/Красные карточки</th>
                    <th>Забитые мячи</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Получение данных из таблицы players
                $sql = "SELECT * FROM players";
                $result = $con->query($sql);

                // Проверка, есть ли данные в таблице
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='uploads/" . htmlspecialchars($row['image']) . "' class='logo-img'></td>";
                        echo "<td>" . htmlspecialchars($row['fio']) . "</td>";
                        echo "<td>" . (int)$row['age'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['num_pos']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cards']) . "</td>";
                        echo "<td>" . (int)$row['goals'] . "</td>";
                        echo "<td>
                                <button class='btn btn-info btn-sm' data-toggle='modal' data-target='#editItemModal' data-id='" . $row['id'] . "' data-fio='" . htmlspecialchars($row['fio']) . "' data-age='" . $row['age'] . "' data-num_pos='" . htmlspecialchars($row['num_pos']) . "' data-cards='" . htmlspecialchars($row['cards']) . "' data-goals='" . $row['goals'] . "' data-image='" . htmlspecialchars($row['image']) . "'>Редактировать</button>
                                <a href='?delete=" . $row['id'] . "' class='btn btn-danger btn-sm'>Удалить</a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted'>Нет данных для отображения.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Edit Item -->
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Редактировать игрока</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="playerId">
                        <div class="form-group">
                            <label for="fio">ФИО</label>
                            <input type="text" class="form-control" name="fio" id="fio" required>
                        </div>
                        <div class="form-group">
                            <label for="age">Возраст</label>
                            <input type="number" class="form-control" name="age" id="age" required>
                        </div>
                        <div class="form-group">
                            <label for="num_pos">Номер, позиция</label>
                            <input type="text" class="form-control" name="num_pos" id="num_pos" required>
                        </div>
                        <div class="form-group">
                            <label for="cards">Желтые/Красные карточки</label>
                            <input type="text" class="form-control" name="cards" id="cards" required>
                        </div>
                        <div class="form-group">
                            <label for="goals">Забитые мячи</label>
                            <input type="number" class="form-control" name="goals" id="goals" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Изображение</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <input type="hidden" name="current_image" id="current_image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" name="edit_player" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Для редактирования данных игрока в модальном окне
        $('#editItemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var playerId = button.data('id');
            var fio = button.data('fio');
            var age = button.data('age');
            var numPos = button.data('num_pos');
            var cards = button.data('cards');
            var goals = button.data('goals');
            var image = button.data('image');

            var modal = $(this);
            modal.find('#playerId').val(playerId);
            modal.find('#fio').val(fio);
            modal.find('#age').val(age);
            modal.find('#num_pos').val(numPos);
            modal.find('#cards').val(cards);
            modal.find('#goals').val(goals);
            modal.find('#current_image').val(image);
        });
    </script>
</body>
</html>
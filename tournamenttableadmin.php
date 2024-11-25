<?php require_once('includes/showMessage.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
    exit();
}
require 'includes/functions.php';
include("connection.php");

// Функция для обновления мест в таблице
function updatePlaces($con) {
    $sql = "SELECT airport_id 
            FROM airport 
            ORDER BY points DESC, goal_difference DESC, scored_goals DESC";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $place = 1;
        while ($row = $result->fetch_assoc()) {
            $updateSql = "UPDATE airport SET place = ? WHERE airport_id = ?";
            $stmt = $con->prepare($updateSql);
            $stmt->bind_param("ii", $place, $row['airport_id']);
            $stmt->execute();
            $stmt->close();
            $place++;
        }
    }
}

// Обработка удаления клуба
if (isset($_POST['confirm_delete_airport'])) {
    $airportId = $_POST['delete_airport_id'];

    $deleteSql = "DELETE FROM airport WHERE airport_id = ?";
    $stmt = $con->prepare($deleteSql);
    $stmt->bind_param("i", $airportId);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Клуб успешно удален!";
    } else {
        $_SESSION['message'] = "Ошибка при удалении клуба.";
    }
    $stmt->close();

    // Обновляем места
    updatePlaces($con);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка редактирования клуба
if (isset($_POST['edit_airport'])) {
    $airportId = $_POST['airport_id'];
    $game = $_POST['edit_game'];
    $win = $_POST['edit_win'];
    $defeat = $_POST['edit_defeat'];
    $draw = $_POST['edit_draw'];
    $scoredGoals = $_POST['edit_scored_goals'];
    $missedGoals = $_POST['edit_missed_goals'];

    // Вычисление очков
    $points = ($win * 3) + ($draw * 1);

    $updateSql = "UPDATE airport SET game = ?, win = ?, defeat = ?, draw = ?, 
                  scored_goals = ?, missed_goals = ?, points = ? 
                  WHERE airport_id = ?";
    $stmt = $con->prepare($updateSql);
    $stmt->bind_param("iiiiiiis", $game, $win, $defeat, $draw, $scoredGoals, $missedGoals, $points, $airportId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Клуб успешно обновлен!";
    } else {
        $_SESSION['message'] = "Ошибка при обновлении клуба.";
    }
    $stmt->close();

    // Обновляем места
    updatePlaces($con);

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
    <title>Турнирная таблица</title>
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

        .rank-list {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include('includes/admin-nav.php'); ?>
    <div class="container mt-5">
        <h2 style="text-align: center;">Турнирная таблица</h2>

        <!-- Список призеров -->
        <div class="rank-list">
            <h3>Призеры турнира</h3>
            <ul>
                <?php
                // Выбираем первые 3 клуба
                $sqlTop3 = "SELECT airport_name, place FROM airport ORDER BY place ASC LIMIT 3";
                $resultTop3 = $con->query($sqlTop3);

                if ($resultTop3->num_rows > 0) {
                    while ($row = $resultTop3->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row["place"]) . " место: " . htmlspecialchars($row["airport_name"]) . "</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <!-- Список клубов, покидающих турнир -->
        <div class="rank-list">
            <h3>Клубы, покидающие турнир</h3>
            <ul>
                <?php
                // Выбираем последние 2 клуба
                $sqlBottom2 = "SELECT airport_name, place FROM airport ORDER BY place DESC LIMIT 2";
                $resultBottom2 = $con->query($sqlBottom2);

                if ($resultBottom2->num_rows > 0) {
                    while ($row = $resultBottom2->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row["place"]) . " место: " . htmlspecialchars($row["airport_name"]) . "</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <!-- Список команд с наименьшей и наибольшей разницей мячей -->
        <div class="rank-list">
            <h3>Команды с наибольшей и наименьшей разницей мячей</h3>
            <ul>
                <?php
                // Выбираем команду с наибольшей разницей мячей
                $sqlMaxGoalDifference = "SELECT airport_name, goal_difference FROM airport ORDER BY goal_difference DESC LIMIT 1";
                $resultMaxGoalDifference = $con->query($sqlMaxGoalDifference);

                if ($resultMaxGoalDifference->num_rows > 0) {
                    $rowMax = $resultMaxGoalDifference->fetch_assoc();
                    echo "<li>Наибольшая разница мячей: " . htmlspecialchars($rowMax["airport_name"]) . " с разницей " . htmlspecialchars($rowMax["goal_difference"]) . "</li>";
                }

                // Выбираем команду с наименьшей разницей мячей
                $sqlMinGoalDifference = "SELECT airport_name, goal_difference FROM airport ORDER BY goal_difference ASC LIMIT 1";
                $resultMinGoalDifference = $con->query($sqlMinGoalDifference);

                if ($resultMinGoalDifference->num_rows > 0) {
                    $rowMin = $resultMinGoalDifference->fetch_assoc();
                    echo "<li>Наименьшая разница мячей: " . htmlspecialchars($rowMin["airport_name"]) . " с разницей " . htmlspecialchars($rowMin["goal_difference"]) . "</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Форма поиска -->
        <div class="d-flex justify-content-center mb-4">
            <input type="text" id="search" class="form-control mr-2" style="width: 300px;" placeholder="Поиск клуба">
        </div>

        <table class="table table-striped" id="airportTable">
            <thead class="table-dark">
                <tr>
                    <th>Место</th>
                    <th>Логотип</th>
                    <th>Название клуба</th>
                    <th>Игры</th>
                    <th>Победы</th>
                    <th>Поражения</th>
                    <th>Ничья</th>
                    <th>Забитые мячи</th>
                    <th>Пропущенные мячи</th>
                    <th>Разница мячей</th>
                    <th>Очки</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlAirports = "SELECT airport_id, airport_name, logo, place, game, win, defeat, draw, scored_goals, missed_goals, goal_difference, points
                                FROM airport
                                ORDER BY place ASC";
                $resultAirports = $con->query($sqlAirports);

                if ($resultAirports->num_rows > 0) {
                    while ($rowAirport = $resultAirports->fetch_assoc()) {
                        echo "<tr class='airport-row'>";
                        echo "<td>" . htmlspecialchars($rowAirport["place"]) . "</td>";
                        echo "<td><img src='uploads/" . htmlspecialchars($rowAirport["logo"]) . "' class='logo-img' alt='Логотип'></td>";
                        echo "<td>" . htmlspecialchars($rowAirport["airport_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["game"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["win"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["defeat"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["draw"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["scored_goals"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["missed_goals"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["goal_difference"]) . "</td>";
                        echo "<td>" . htmlspecialchars($rowAirport["points"]) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-info btn-sm edit-airport' data-id='" . $rowAirport["airport_id"] . "' 
                              data-game='" . htmlspecialchars($rowAirport["game"]) . "' 
                              data-win='" . htmlspecialchars($rowAirport["win"]) . "' 
                              data-defeat='" . htmlspecialchars($rowAirport["defeat"]) . "' 
                              data-draw='" . htmlspecialchars($rowAirport["draw"]) . "' 
                              data-scored-goals='" . htmlspecialchars($rowAirport["scored_goals"]) . "' 
                              data-missed-goals='" . htmlspecialchars($rowAirport["missed_goals"]) . "' 
                              data-toggle='modal' data-target='#editAirportModal'>Редактировать</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>Нет данных для отображения.</td></tr>";
                }
                ?>
            </tbody>
        </table>

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
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="airport_id" id="airport_id">
                            <div class="form-group">
                                <label for="edit_game">Игры:</label>
                                <input type="number" class="form-control" name="edit_game" id="edit_game" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_win">Победы:</label>
                                <input type="number" class="form-control" name="edit_win" id="edit_win" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_defeat">Поражения:</label>
                                <input type="number" class="form-control" name="edit_defeat" id="edit_defeat" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_draw">Ничьи:</label>
                                <input type="number" class="form-control" name="edit_draw" id="edit_draw" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_scored_goals">Забитые мячи:</label>
                                <input type="number" class="form-control" name="edit_scored_goals" id="edit_scored_goals" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_missed_goals">Пропущенные мячи:</label>
                                <input type="number" class="form-control" name="edit_missed_goals" id="edit_missed_goals" required>
                            </div>
                            <button type="submit" name="edit_airport" class="btn btn-primary">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).on('click', '.edit-airport', function() {
            $('#airport_id').val($(this).data('id'));
            $('#edit_game').val($(this).data('game'));
            $('#edit_win').val($(this).data('win'));
            $('#edit_defeat').val($(this).data('defeat'));
            $('#edit_draw').val($(this).data('draw'));
            $('#edit_scored_goals').val($(this).data('scored-goals'));
            $('#edit_missed_goals').val($(this).data('missed-goals'));
        });

        $("#search").on("input", function() {
            var value = $(this).val().toLowerCase();
            $("#airportTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
</body>

</html>

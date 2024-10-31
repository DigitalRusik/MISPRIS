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
    <title>Управление пользователями</title>
    <link rel="stylesheet" href="css/style.css" />
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    
</head>

<body>
    <?php include('includes/admin-nav.php'); ?>

    <div class="container mt-5">
        <h2 style="text-align: center;">Администраторы</h2>

        <style>
        table {
            margin-left: 10%; /* Сдвигаем таблицу вправо */
           
        }
        </style>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th >Имя</th>
                    <th>Почта</th>
                    <th class="text-center">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("connection.php");

                // код удаления пользователя
                if (isset($_POST["confirm_delete_user"])) {
                    $deleteUserEmail = $_POST["delete_user_email"];

                    // Инициализация переменной пользовательского типа
                    $userType = '';

                    // Определение SQL-запроса, чтобы проверить, в какой таблице находится электронное письмо
                    $checkAdminSql = "SELECT * FROM admin WHERE email = '$deleteUserEmail'";
                    $checkCustomerSql = "SELECT * FROM customer WHERE email = '$deleteUserEmail'";
                    $checkAirlineSql = "SELECT * FROM airline WHERE email = '$deleteUserEmail'";

                    // Проверка, существует ли адрес электронной почты в таблице администратора
                    $resultAdmin = $con->query($checkAdminSql);
                    if ($resultAdmin->num_rows > 0) {
                        $userType = 'admin';
                        // Проверка общего количества администраторов в базе данных
                        $totalAdminSql = "SELECT COUNT(*) AS admin_count FROM admin";
                        $resultTotalAdmin = $con->query($totalAdminSql);

                        if ($resultTotalAdmin->fetch_assoc()['admin_count'] <= 1) {
                            setSessionMessage("At least one admin required");
                            header('location: users.php');
                            exit;
                        }

                    }

                    // Проверка, существует ли почта в таблице клиентов
                    $resultCustomer = $con->query($checkCustomerSql);
                    if ($resultCustomer->num_rows > 0) {
                        $userType = 'customer';
                    }

                    // Проверка, существует ли почта в таблице авиакомпаний
                    $resultAirline = $con->query($checkAirlineSql);
                    if ($resultAirline->num_rows > 0) {
                        $userType = 'airline';
                    }

                    // Если тип пользователя идентифицирован, приступайте к удалению
                    if (!empty($userType)) {
                        // Определение SQL-запроса в зависимости от типа пользователя
                        $deleteSql = "DELETE FROM $userType WHERE email = '$deleteUserEmail'";

                        // Выполнение SQL-запрос
                        if ($con->query($deleteSql) === TRUE) {
                            setSessionMessage("Пользователь успешно удален");
                        } else {
                            setSessionMessage("Ошибка при удалении пользователя: " . $con->error, "error");
                        }
                    } else {
                        setSessionMessage("Пользователь не найден", "error");
                    }

                    // Перенаправление на текущую страницу, чтобы обновить список пользователей
                    header('location: users.php');
                }

                // Отображение пользователей из таблицы администратора
                $sqlAdmins = "SELECT * FROM admin";
                $resultAdmins = $con->query($sqlAdmins);

                while ($rowAdmin = $resultAdmins->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='text-wrap'>" . $rowAdmin["admin_name"] . "</td>";
                    echo "<td class='text-wrap'>" . $rowAdmin["email"] . "</td>";
                    echo "<td class='text-center'  class='text-wrap'>";
                    echo "<button class='btn btn-danger btn-sm delete-user' data-email='" . $rowAdmin["email"] . "' data-toggle='modal' data-target='#deleteUserModal'>Удалить</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Удаление пользователя -->
        <form action="" method="POST">
            <input type="hidden" name="delete_user_email" id="delete_user_email" value="">
            <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog"
                aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteUserModalLabel">Подтвердите удаление</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        Вы уверены, что хотите удалить этого администратора?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-danger" name="confirm_delete_user">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="container mt-5">
        <h2 style="text-align: center;">Клиенты</h2>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Имя</th>
                    <th>Почта</th>
                    <th class="text-center">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Отображение пользователей из таблицы клиентов
                $sqlCustomers = "SELECT * FROM customer";
                $resultCustomers = $con->query($sqlCustomers);

                while ($rowCustomer = $resultCustomers->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='text-wrap'>" . $rowCustomer["customer_name"] . "</td>";
                    echo "<td  class='text-wrap'>" . $rowCustomer["email"] . "</td>";
                    echo "<td class='text-center'  class='text-wrap'>";
                    echo "<button class='btn btn-danger btn-sm delete-user' data-email='" . $rowCustomer["email"] . "' data-toggle='modal' data-target='#deleteUserModal'>Удалить</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="container mt-5">
        <h2 style="text-align: center;">Авиакомпании</h2>

        <table class="table table-striped">
            <thead  class="table-dark">
                <tr>
                    <th>Название</th>
                    <th>Почта</th>
                    <th class="text-center">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Отображение пользователей из таблицы авиакомпаний
                $sqlAirlines = "SELECT * FROM airline";
                $resultAirlines = $con->query($sqlAirlines);

                while ($rowAirline = $resultAirlines->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td  class='text-wrap'>" . $rowAirline["airline_name"] . "</td>";
                    echo "<td  class='text-wrap'>" . $rowAirline["email"] . "</td>";
                    echo "<td class='text-center'  class='text-wrap'>";
                    echo "<button class='btn btn-danger btn-sm delete-user' data-email='" . $rowAirline["email"] . "' data-toggle='modal' data-target='#deleteUserModal'>Удалить</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


    <!-- Bootstrap and jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript to handle modals -->
    <script>
        // Нажатие обработчика событий для кнопок удаления
        $(document).on("click", ".delete-user", function () {
            var deleteUserEmail = $(this).data('email');
            $('#delete_user_email').val(deleteUserEmail);
        });
    </script>
</body>

</html>
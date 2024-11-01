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
    <title>Добавление авиакомпании</title>
    <link rel="stylesheet" href="css/style.css" />
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  </head>

<body>

<?php include('includes/admin-nav.php') ?>;
    <main>

        <div class="container mt-5">
        <h2 style="text-align: center;">Авиакомпании (Стадионы)</h2>

            <!-- Добавление кнопки -->

            <div class="d-flex justify-content-center" style="height:">
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAirlineModal">Добавить авиакомпанию</button>
</div>

<!-- Форма поиска авиакомпании по названию -->
<form method="GET" class="d-flex justify-content-center mt-3">
            <input type="text" class="form-control w-50" name="search_airline" placeholder="Введите название авиакомпании" value="<?php echo isset($_GET['search_airline']) ? $_GET['search_airline'] : ''; ?>">
            <button type="submit" class="btn btn-primary ml-2">Поиск</button>
        </form>

<style>
    table {
        margin-left: 10%; /* Сдвигаем таблицу вправо */
        margin-top: 20px; /* Добавляем отступ сверху для таблицы */
    }
</style>
            <table class="table table-striped" style="margin-bottom:0px;">
            <thead class="table-dark">
                <tr>
                    <th style="width: 20%;">Почта</th>
                    <th>Пароль</th>
                    <th>Название стадиона</th>
                    <th>Логотип</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>

                    <?php
                    include("connection.php");


                    //операция добавления авиакомпании
                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_airline"])) {
                        $newEmail = $_POST["new_email"];
                        $newPassword = $_POST["new_password"];
                        $newAirlineName = $_POST["new_airline_name"];

                        // Обработка загруженного изображени
                        $targetDir = "uploads/";
                        $targetFile = $targetDir . basename($_FILES["new_logo"]["name"]);

                        // Проверка, существует ли каталог, если нет, создание его
                        if (!file_exists($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }

                        if (move_uploaded_file($_FILES["new_logo"]["tmp_name"], $targetFile)) {
                            $newLogoPath = $targetFile;

                            // Проверка, существует ли эта почта в таблице
                            $emailCheckSql = "SELECT * FROM airline WHERE email = '$newEmail'";
                            $emailCheckResult = $con->query($emailCheckSql);

                            // Проверка, существует ли название в таблице
                            $nameCheckSql = "SELECT * FROM airline WHERE airline_name = '$newAirlineName'";
                            $nameCheckResult = $con->query($nameCheckSql);

                            if ($emailCheckResult->num_rows > 0) {
                                // Авиакомпания с таким же адресом электронной почты уже существует
                                setSessionMessage("Авиакомпания с такой электронной почтой уже зарегистрирована");
                            } elseif ($nameCheckResult->num_rows > 0) {
                                // Авиакомпания с таким названием уже существует
                                setSessionMessage("Название авиакомпании уже занято");
                            } else {
                                //Повторяющиеся записи не найдены, выполните запрос INSERT query
                                $insertSql = "INSERT INTO airline VALUES ('$newEmail', '$newPassword', '$newAirlineName', '$newLogoPath')";
                                if ($con->query($insertSql) === TRUE) {
                                    setSessionMessage("Авиакомпания успешно добавлена");
                                } else {
                                    setSessionMessage("При добавлении записи произошла ошибка");
                                }
                            }

                            header('location:airlines.php');

                        } else {
                            setSessionMessage("Ошибка при загрузке логотипа");
                            header('location:airlines.php');
                        }
                    }

                    // Операция обновления
                    if (isset($_POST["edit_airline"])) {
                        $editEmail = $_POST["edit_email"];
                        $updateSql = "UPDATE airline SET ";

                        $updateValues = array(); // Массив для хранения обновляемых полей
                    
                        // Проверка, был ли введен новый пароль
                        if (!empty($_POST["edit_password"])) {
                            $editPassword = $_POST["edit_password"];
                            $updateValues[] = "pass='$editPassword'";
                        }

                        // Проверка, было ли указано новое название авиакомпании
                        $editAirlineName = $_POST["edit_airline_name"];
                        if (!empty($_POST["edit_airline_name"])) {
                            $nameCheckSql = "SELECT * FROM airline WHERE airline_name = '$editAirlineName'";
                            $nameCheckResult = $con->query($nameCheckSql);
                            if ($nameCheckResult->num_rows > 0) {
                                // Авиакомпания с таким названием уже существует
                                setSessionMessage("Название авиакомпании уже занято");
                                header('location: airlines.php');
                                exit();
                                
                            }
                            $updateValues[] = "airline_name='$editAirlineName'";
                        }

                        // Проверка, было ли загружено новое изображение логотипа
                        if (!empty($_FILES["edit_logo"]["name"])) {
                            // Обработка загруженного изображения
                            $targetDir = "uploads/";
                            $targetFile = $targetDir . basename($_FILES["edit_logo"]["name"]);

                            // Проверка, существует ли каталог, если нет, создайте его
                            if (!file_exists($targetDir)) {
                                mkdir($targetDir, 0777, true);
                            }

                            if (move_uploaded_file($_FILES["edit_logo"]["tmp_name"], $targetFile)) {
                                $editLogoPath = $targetFile;
                                $updateValues[] = "logo='$editLogoPath'";
                            } else {
                                setSessionMessage("Ошибка при обновлении логотипа");
                                exit;
                            }
                        }
                        
                        // Объединение значения для обновления в SQL-запросе
                        $updateSql .= implode(", ", $updateValues);
                        $updateSql .= " WHERE email='$editEmail'";
                        

                         // Проверка, были ли предоставлены какие-либо значения для обновления
    if (empty($updateValues)) {
        setSessionMessage("Значения для обновления не указаны");
        header('location: airlines.php');
        exit();
    }

                        if ($con->query($updateSql) === TRUE) {
                            setSessionMessage("Запись успешно обновлена");
                            header('location: airlines.php');
                        } else {
                            echo "<script>showModal('errorModal', 'Error updating record: " . $con->error . "');</script>";
                            header('location: airlines.php');
                        }
                    }



                    if ($con->connect_error) {
                        die("connection failed: " . $con->connect_error);
                    }

                    // Операция удаления
                    if (isset($_GET["delete"])) {
                        $deleteEmail = $_GET["delete"];

                        // Удалите запись с указанным адресом электронной почты
                        $deleteSql = "DELETE FROM airline WHERE email = '$deleteEmail'";

                        if ($con->query($deleteSql) === TRUE) {
                            setSessionMessage("Авиакомпания успешно удалена");
                        } else {
                            echo 'Error deleting record: ' . $con->error;
                        }
                        header('location: airlines.php');
                    }



                    // Отображение авиакомпаний в таблице
                    $sqlAirlines = "SELECT * FROM airline";
                    $resultAirlines = $con->query($sqlAirlines);
                    ?>
                  
                            <?php
                            while ($rowAirline = $resultAirlines->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $rowAirline["email"] . "</td>";
                                echo "<td>" . $rowAirline["pass"] . "</td>";
                                echo "<td>" . $rowAirline["airline_name"] . "</td>";
                                echo "<td><img src='" . $rowAirline["logo"] . "' alt='Airline Logo' height='50'></td>";
                                echo "<td>";
                                echo "<button class='btn btn-primary btn-sm edit-record' data-id='" . $rowAirline["email"] . "' data-toggle='modal' data-target='#editAirlineModal'>Редактировать</button>";
                                echo "<button class='btn btn-danger btn-sm delete-record' data-id='" . $rowAirline["email"] . "' data-toggle='modal' data-target='#deleteAirlineModal'>Удалить</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <?php
                    // Закрытие подключения к базе данных
                    $con->close();
                    ?>

                    <!-- Добавить авиакомпанию -->
                    <div class="modal fade" id="addAirlineModal" tabindex="-1" role="dialog"
                        aria-labelledby="addAirlineModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAirlineModalLabel">Добавить авиакомпанию</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="airlines.php" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="new_email">Почта</label>
                                            <input type="email" class="form-control" id="new_email" name="new_email"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password">Пароль</label>
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_airline_name">Название авиакомпании</label>
                                            <input type="text" class="form-control" id="new_airline_name"
                                                name="new_airline_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_logo">Логотип</label>
                                            <input type="file" class="form-control-file" id="new_logo" name="new_logo"
                                                accept="image/*" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Отмена</button>
                                            <button type="submit" class="btn btn-primary"
                                                name="add_airline">Сохранить</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Редактирование авиакомпании -->
                    <div class="modal fade" id="editAirlineModal" tabindex="-1" role="dialog"
                        aria-labelledby="editAirlineModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editAirlineModalLabel">Редактировать информацию</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="airlines.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="edit_email" id="edit_email_hidden">
                                        <div class="form-group">
                                            <label for="edit_password">Пароль</label>
                                            <input type="password" class="form-control" id="edit_password"
                                                name="edit_password">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_airline_name">Название авиакомпании</label>
                                            <input type="text" class="form-control" id="edit_airline_name"
                                                name="edit_airline_name">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_logo">Логотип</label>
                                            <input type="file" class="form-control-file" id="edit_logo" name="edit_logo"
                                                accept="image/*">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Отмена</button>
                                            <button type="submit" class="btn btn-primary" name="edit_airline">Сохранить изменения
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Удаление авиакомпании -->
                    <div class="modal fade" id="deleteAirlineModal" tabindex="-1" role="dialog"
                        aria-labelledby="deleteAirlineModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteAirlineModalLabel">Подтвердите удаление</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                Вы уверены, что хотите удалить эту авиакомпанию?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                                    <button type="button" class="btn btn-danger" id="confirmDelete">Удалить</button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- //Success Modal 
  <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="successModalBody">
                        Record operation was successful.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        // Error Modal 
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="errorModalBody">
                        An error occurred during the operation.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

                    <!-- Bootstrap and jQuery Scripts -->
                    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


                    <!-- JavaScript to handle modals and delete operation -->
                    <script>
                        // function showSuccessModal(message) {
                        //     $('#successModalBody').text(message);
                        //     $('#successModal').modal('show');
                        // }

                        // function showErrorModal(message) {
                        //     $('#errorModalBody').text(message);
                        //     $('#errorModal').modal('show');
                        // }
                        // Функция, позволяющая указать адрес электронной почты записи для редактирования в режиме редактирования
                        function setEditEmail(editEmail) {
                            $('#edit_email_hidden').val(editEmail);
                        }

                        // Нажатие обработчика событий для кнопок редактирования
                        $(document).on("click", ".edit-record", function () {
                            var editEmail = $(this).data('id');
                            setEditEmail(editEmail);
                        });


                        // Функция для установки идентификатора записи, подлежащей удалению в модели
                        $(document).on("click", ".delete-record", function () {
                            var email = $(this).data('id');
                            $("#confirmDelete").data('id', email);
                        });

                        $(document).on("click", "#confirmDelete", function () {
                            var email = $(this).data('id');
                            window.location.href = "?delete=" + email;

                        });



                    </script>

</body>

</html>
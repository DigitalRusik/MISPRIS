<?php include('includes/header.php'); 
 require_once('includes/showMessage.php');
?>
<div class="container">
    <h2>Смена пароля</h2>
    <div class="form-and-image-container">
        <div class="image-container">
            <img src="images/changePassword.jpg" alt="Change Password Image">
        </div>
        <form action="change-password.php" method="POST" class="password-form">
            <div class="form-group">
                <label for="old_password">Старый пароль:</label>
                <input type="password" name="old_password" id="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Новый пароль:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Подтвердить новый пароль:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" name="change_password">Сменить пароль</button>
        </form>
    </div>
</div>

<?php

if (!isset($_SESSION['logged_in'])) {
    // Перенаправить $User, который не вошел в систему, на страницу входа в систему
    header('Location: index.php');
    exit();
}

if (isset($_POST['change_password'])) {
    include('connection.php');

    // Получение данных
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['email'];
    $User = $_SESSION['user_type'];
    // Извлечение текущего пароля пользователя из базы данных
    $query = "SELECT pass FROM $User WHERE email = '$email'";
    $result = $con->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $current_password = $row['pass'];

        // Подтверждение старого пароля
        if ($old_password === $current_password) {
            // Проверка, совпадают ли новый пароль и подтверждение
            if ($new_password === $confirm_password) {
                // Обновление пароля в базе данных
                $update_query = "UPDATE $User SET pass = '$new_password' WHERE email = '$email'";
                if ($con->query($update_query) === TRUE) {
                    $messageText = "Пароль успешно изменен.";
                    echo '<script>var jsMessageText = "' . $messageText . '";</script>';
                    // echo '<meta http-equiv="refresh" content="2;url=customer-dashboard.php">';
                    echo '<meta http-equiv="refresh" content="2;url=' . $User . '-dashboard.php">'; // Redirect to the appropriate dashboard
                } else {
                    $messageText = "Ошибка при обновлении пароля:" . $con->error;
                    echo '<script>var jsMessageText = "' . $messageText . '";</script>';
                }
            } else {
                $messageText = "Новый пароль и подтверждение не совпадают.";
                echo '<script>var jsMessageText = "' . $messageText . '";</script>';
            }
        } else {
            $messageText = "Старый пароль неверен.";
            echo '<script>var jsMessageText = "' . $messageText . '";</script>';
        }
    } else {
        $messageText = "Что-то пошло не так";
        echo '<script>var jsMessageText = "' . $messageText . '";</script>';
    }

    // Закрытие соединения
    $con->close();
}
?>
<?php include('includes/footer.php'); ?>

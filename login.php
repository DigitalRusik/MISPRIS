<?php include('includes/header.php');
include('includes/showMessage.php');
?>
<?php
//
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('location:logout.php');
}
?>

<?php
// Начало сеанса в начале страницы
// session_start();

// Проверка, задан ли параметр 'option', и сохранение его в переменной сеанса
if (isset($_GET['option'])) {
    $_SESSION['option'] = $_GET['option'];
}
// else {
//     // $_SESSION['option'] = 'customer';
//     header('location:index.php');
// }
// echo $_SESSION['option'];
?>

<div class="wrapper" style="background-image: url('images/signupback.jpg');">
    <div class="inner">
        <div class="image-holder">
            <img src="images/loginfront.jpg" alt="">
        </div>
        <form action="login.php" method="POST">
            <h3>
                <?php echo $_SESSION['option']; ?> Логин
            </h3>
            <div class="form-wrapper">
                <input type="text" name="email_or_username" placeholder="Почта или имя пользователя" class="form-control"
                    required>
            </div>
            <div class="form-wrapper">
                <input type="password" name="password" placeholder="Пароль" class="form-control" id="password"
                    required>
                <span toggle="#password" class="password-toggle"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                    👀
                </span>
            </div>

            <script src="js/tooglePass.js"> </script>
            <button type="submit" name="login">Авторизоваться
                <i class="zmdi zmdi-arrow-right"></i>
            </button>
        </form>
    </div>
</div>
<?php
if (isset($_POST['login'])) {

    include('connection.php');

    // Получение данных
    $email_or_username = $_POST['email_or_username'];
    $password = $_POST['password'];

    $user_type = strtolower($_SESSION['option']);

    // Проверка, существует ли в базе данных адрес электронной почты или имя пользователя
    $User = "{$user_type}_name";
    // echo $User;
    $isEmail = "SELECT * FROM $user_type WHERE (email = '$email_or_username' AND BINARY pass = '$password')"; // using BINARY to make the pass column case-sensitive
    $isUsername = "SELECT * FROM $user_type WHERE ($User = '$email_or_username' AND BINARY pass = '$password')";
    $qEmail = $con->query($isEmail);
    $qUsername = $con->query($isUsername);
    if ($qEmail->num_rows == 1 or $qUsername->num_rows == 1) {
        // Начало нового сеанса или возобновление существующего
        session_destroy();
        session_start();

        // Хранить информацию, относящуюся к пользователю, в переменных сеанса
        $_SESSION['logged_in'] = true;
        $_SESSION['user_type'] = strtolower($user_type);
        // echo $_SESSION['user_type'];
        if ($qEmail->num_rows == 1) {
            $_SESSION['email'] = $email_or_username;

        } else {
            $q = "SELECT * FROM $user_type WHERE $User = '$email_or_username'";
            $result = $con->query($q);
            $row = $result->fetch_assoc();
            $rowValue = $row['email'];
            $_SESSION['email'] = $rowValue;
        }
        // Перенаправление пользователя на панель
        header("Location: {$user_type}-dashboard.php");
    } else {
        $query = "select * from $user_type where (email = '$email_or_username' or $User = '$email_or_username')";
        $result = $con->query($query);
        if($result->num_rows > 0) {
            $messageText = "Неверный пароль.";
        } else {
            $messageText = "Пользователь не существует, пожалуйста, зарегистрируйтесь";
        }
        echo '<script>var jsMessageText = "' . $messageText . '";</script>';
    }

    // Закрытие соединения
    $con->close();
}
?>
<?php include('includes/footer.php'); ?>
       

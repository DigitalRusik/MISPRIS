<?php require_once 'includes/header.php'; ?>
<?php require ('includes/showMessage.php'); ?>
<?php 
    // if user try to access the signup page by typing the signup page url manyally while s/he is logged in,
    // then we can prevent it. S/he will be logged out if s/he try this. 
    //Since we didn't add an nav option to access the signup page then why s/he should access this? So we're restrciting him/ her. 
   if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        header('location:logout.php');                                      
   }
?>
<div class="wrapper" style="background-image: url('images/signupback.jpg');">
    <div class="inner">
        <div class="image-holder">
            <img src="images/signupfront.jpg" alt="">
        </div>
        <form action="" method="POST">
            <h3>Регистрация пользователя</h3>
            <div class="form-group">
                <input type="text" name="first_name" placeholder="Имя" class="form-control" required>
                <input type="text" name="last_name" placeholder="Фамилия" class="form-control" required>
                <span id="username_message"></span>
            </div>
            <div class="form-wrapper">
                <input type="text" name="username" placeholder="Имя пользователя" class="form-control" id="username" required>
            </div>
            <div class="form-wrapper">
                <input type="email" name="email" placeholder="Почта" class="form-control" required>
            </div>
            <div class="form-wrapper">
                <input type="number" name="phone" placeholder="Телефон" class="form-control" required>
            </div>
            <div class="form-wrapper">
                <select name="gender" class="form-control" required>
                    <option value="" disabled selected>Пол</option>
                    <option value="male">Мужской</option>
                    <option value="female">Женский</option>
                    <option value="other">Другой</option>
                </select>
                <i class="zmdi zmdi-caret-down" style="font-size: 17px"></i>
            </div>
            <div class="form-wrapper">
                <input type="password" name="password" placeholder="Пароль" class="form-control" id="password"
                    required>
                <span toggle="#password" class="password-toggle"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">👀</span>
            </div>
            <div class="form-wrapper">
                <input type="password" name="confirm_password" placeholder="Подтвердить пароль" class="form-control"
                    id="confirm-password" required>
                <span toggle="#confirm-password" class="password-toggle"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">👀</span>
            </div>
            <div class="password-match-container" style="position: relative;">
                <div id="password-match-message" <div id="password-match-message"
                    style="color: red; font-size: smaller; position: absolute; margin-top: -5%;"></div>

            </div>
            <script src="js/tooglePass.js"></script>
            <script src="js/passwordMatching.js"></script>
            <button type="submit" name="submit">Зарегистрироваться <i class="zmdi zmdi-arrow-right"></i></button>
        </form>
    </div>
</div>
<?php
if (isset($_POST['submit'])) {
    include('connection.php');

    // Получение данных 
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ($password != $confirm_password) {
        $messageText = "Password don't match";
        echo '<script>var jsMessageText = "' . $messageText . '";</script>';
       
        exit();
    }
    // Проверка, существует ли адрес электронной почты или имя пользователя в базе данных
    $check_query = "SELECT * FROM customer WHERE email = '$email' OR customer_name = '$username'";
    $result = $con->query($check_query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['email'] == $email) {
            $messageText = "Адрес электронной почты уже зарегистрирован в системе, пожалуйста, используйте другую.";
        } else {
            $messageText = "Имя пользователя занято, пожалуйста, выберите другое имя пользователя.";
        }

        echo '<script>var jsMessageText = "' . $messageText . '";</script>';
       
    } else {
        // SQL-запрос для вставки данных в таблицу пользователей
        $insert_query = "INSERT INTO customer VALUES ('$first_name', '$last_name', '$username', '$email', $phone, '$gender', '$password')";

        // Выполнение добавления
        if (mysqli_query($con, $insert_query)) {
            $messageText = "Поздравляем! Вы успешно зарегистрированы";
            echo '<script>var jsMessageText = "' . $messageText . '";</script>';
           
            echo '<meta http-equiv="refresh" content="3;url=login.php">'; //ожидание 3 секунды, пока появится сообщение об успешном завершении, а затем перенаправляем.
            // Перенаправление после успешной регистрации
            // header('location: index.php');
            // exit(); // Make sure to exit after the header() call
        } else {
            // Вывод сообщения об ошибке в браузер или запись его в журнал
            echo "Error: " . $insert_query . "<br>" . mysqli_error($con);
        }
    }

    // Закрытие соединение
    $con->close();
}
?>
<?php include('includes/footer.php'); ?>
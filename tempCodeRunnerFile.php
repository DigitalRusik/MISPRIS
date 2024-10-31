<?php
// Проверка, отправлена ли форма
if(isset($_POST['submit'])){
    // Сведения о подключении к базе данных
    $db_host = "localhost"; // Предполагается, что база данных размещена локально
    $db_user = "root"; // Пользователь MySQL root по умолчанию
    $db_pass = ""; // Пароль для пользователя root не установлен
    $db_name = "booking"; // Замените на имя вашей базы данных

    // Create a database connection
    $con = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Проверьте, было ли подключение успешным
    if ($con->connect_error) {
        die("connection failed: " . $con->connect_error);
    }

    // Получение данных
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка, существует ли адрес электронной почты или имя пользователя в базе данных
    $check_query = "SELECT * FROM customer WHERE email = ? OR userName = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("ss", $email, $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email or username already exists. Please choose a different email or username.";
    } else {
        // SQL-запрос для вставки данных в таблицу клиентов
        $insert_query = "INSERT INTO customer (firstName, lastName, userName, email, phone, gender, pass, confirmPass) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare and bind the insert statement
        $stmt = $con->prepare($insert_query);
        $stmt->bind_param("ssssisss", $first_name, $last_name, $username, $email, $phone, $gender, $password, $confirm_password);

        // Выполнение инструкцию insert
        if ($stmt->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error: " . $insert_query . "<br>" . $con->error;
        }

        // Закрытие инструкции insert
        $stmt->close();
    }

    // Закротие заявления о проверке и подключении
    $check_stmt->close();
    $con->close();
}
?>
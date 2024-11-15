<?php
session_start();
if(!isset($_SESSION['user_type'])) {
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Боковое меню для панели администратора</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>
<body>
<?php include('includes/admin-nav.php') ?>;
    <main class="main">
        <div class="welcome-text" >
            <h1>Добро пожаловать, администратор!</h1>
            <p>Это наша панель администратора, где мы можем управлять клубами, стадионами, информацией о матчах и многим другим. <br>Используйте боковое меню для навигации по различным разделам.</p>
        </div>
        <!-- Добавление новых фич -->
    </main>

    <script src="js/script.js"></script>
</body>
</html>

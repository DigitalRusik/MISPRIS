<?php
// Получить название текущей страницы или URL-адрес
// Получить текущее название страницы без параметров запроса
// Извлеките имя файла текущего PHP-скрипта без расширения .php
// Объявить переменную в глобальной области видимости
global $current_page;
$current_page = pathinfo(strtok(basename($_SERVER['PHP_SELF']), '?'), PATHINFO_FILENAME);
session_start();
if (isset($_SESSION['user_type'])) {
  $user_type = $_SESSION['user_type'];
  // echo $user_type;
  include("navOptions/{$user_type}-dashboard-nav-options.php");
} else {
  include("navOptions/{$current_page}-nav-options.php");    // выполнение интерполяции означает динамическую конкатенацию строк. 
}
// session_destroy();
if(!isset($_SESSION['option'])) {
  $_SESSION['option'] = 'customer';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>МойФутбол</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <!-- Include the dynamically generated CSS link here -->
  <link rel="stylesheet" type="text/css" href="css/<?php echo $current_page; ?>.css">
  <!-- for all pages -->
  <link rel="stylesheet" type="text/css" href="css/general.css">
</head>

<body>
  <header>

  </header>
  <nav>
    <a class="logo" href="index.php"> <img src="images/Easyfly.png" alt="site-logo"> </a>
    <?php include('navOptions/nav.php') ?>
  </nav>
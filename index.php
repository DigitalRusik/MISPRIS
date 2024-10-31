<?php include('includes/header.php'); ?>

<div>
    <div class="after-nav">
        <div class="fixed-text">Добро пожаловать в Чемпионат</div>
        <div id="text-transition" class="transition-text"></div> <!--span texts from js-->
    </div>
    <script src="js/after-nav-script.js"></script>
</div>

<main>
    <div class="bg-image"></div>
</main>

<div class="partner-airline">
    <h1>Партнеры авиакомпании</h1>

    <?php
    // Включить подключение к файлу .php
    include('connection.php');

    // SQL-запрос для выбора всех авиакомпаний из таблицы airline
    $sql = "SELECT * FROM airline";

    // Выполните запрос
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="partner-airline">';
            echo '<img src="' . $row['logo'] . '" alt="' . $row['airline_name'] . '">';
            echo '<p>' . $row['airline_name'] . '</p>';
            echo '</div>';
        }
    } else {
        echo "No records found";
    }

    // Закрыть подключение к базе данных
    mysqli_close($con);
    ?>
</div>

<?php include('includes/footer.php')?>
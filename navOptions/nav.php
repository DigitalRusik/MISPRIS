<ul>
    <?php
    // Проход по массиву параметров навигации
    foreach ($navOptions as $label => $link) {
        if (is_array($link)) {
            // Обрабатывание выпадающего меню, если значение является массивом
            echo "<li class='dropdown'>$label";
            echo "<ul class='dropdown-content'>";
            foreach ($link as $subLabel => $subLink) {
                echo "<li><a href='$subLink?option=$subLabel'>$subLabel</a></li>";
            }
            echo "</ul></li>"; // Закрытие тега ul для раскрывающегося списка содержимого
        } else {
            // Вывод отдельных ссылок
            echo "<li><a href='$link?option=$label'>$label</a></li>";
        }
    }
    ?>
</ul>

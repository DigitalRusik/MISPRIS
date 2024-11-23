<ul>
    <?php
    // Проверяем, существует ли $navOptions и является ли она массивом
    if (isset($navOptions) && is_array($navOptions)) {
        // Проход по массиву параметров навигации
        foreach ($navOptions as $label => $link) {
            if (is_array($link)) {
                // Обрабатывание выпадающего меню, если значение является массивом
                echo "<li class='dropdown'>" . htmlspecialchars($label);
                echo "<ul class='dropdown-content'>";
                foreach ($link as $subLabel => $subLink) {
                    echo "<li><a href='" . htmlspecialchars($subLink) . "?option=" . urlencode($subLabel) . "'>" . htmlspecialchars($subLabel) . "</a></li>";
                }
                echo "</ul></li>"; // Закрытие тега ul для раскрывающегося списка содержимого
            } else {
                // Вывод отдельных ссылок
                echo "<li><a href='" . htmlspecialchars($link) . "?option=" . urlencode($label) . "'>" . htmlspecialchars($label) . "</a></li>";
            }
        }
    } else {
        // Вывод сообщения или дефолтного контента, если $navOptions не задана
        echo "<li><a href='index.php'>Главная</a></li>";
    }
    ?>
</ul>
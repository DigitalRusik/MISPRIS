<?php
// Функция отображения и очистки сообщения о сеансе
function displaySessionMessage()
{
    if (isset($_SESSION['sessionMessage'])) {
        $messageText = $_SESSION['sessionMessage'];

        echo '<script>var jsMessageText = "' . $messageText . '";</script>';

        // Очистить сообщение о сеансе
        unset($_SESSION['sessionMessage']);
    }
}
// Функция для установки сеансового сообщения
function setSessionMessage($messageText, $messageType = 'info')
{
    $_SESSION['sessionMessage'] = $messageText;
}
?>
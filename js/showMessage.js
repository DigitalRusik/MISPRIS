document.addEventListener("DOMContentLoaded", function () {
    // Создание окна сообщения и элементов
    var messageBox = document.createElement("div");
    messageBox.className = "message-box";

    var messageContent = document.createElement("div");
    messageContent.className = "message-content";

    var messageText = document.createElement("p");
    messageText.textContent = jsMessageText; // Использование переменной JavaScript напрямую

    var closeButton = document.createElement("button");
    closeButton.textContent = "Закрыть";
    closeButton.className = "close-button";
    closeButton.addEventListener("click", function () {
        closeMessageBox();
    });

    messageContent.appendChild(messageText);
    messageContent.appendChild(closeButton);
    messageBox.appendChild(messageContent);
    document.body.appendChild(messageBox);

    // Функция для закрытия окна сообщения
    function closeMessageBox() {
        document.body.removeChild(messageBox);
    }

    // Прослушиватель событий для нажатия клавиши Enter в любом месте страницы
    document.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            closeMessageBox();
        }
    });
});

const textTransition = document.getElementById('text-transition');
const phrases = [
    'Забронируйте Ваши Билеты',
    'Исследуйте места назначения',
    'Приятного Вам путешествия',
    'Безопасное и удобное путешествие',
    'Гарантированные лучшие цены',
    'Круглосуточная поддержка клиентов',
    'Откройте для себя удивительные предложения',
    'Простой процесс бронирования',
    'Путешествуйте с уверенностью'
];
let currentIndex = 0;

// Function to automatically cycle through text phrases
function cycleText() {
    currentIndex = (currentIndex + 1) % phrases.length;
    textTransition.style.opacity = 0;
    setTimeout(() => {
        textTransition.innerHTML = <span>${phrases[currentIndex]}</span>;
        textTransition.style.opacity = 1;
        setTimeout(cycleText, 2000); // Изменение текста каждые 2 секунды (2000 миллисекунд)
    }, 500); // Дождитесь окончания периода перехода, прежде чем обновлять текст
}

// Инициализируйтесь с помощью первой текстовой фразы и начинайте цикл
textTransition.innerHTML = <span>${phrases[currentIndex]}</span>;
setTimeout(cycleText, 2000); // Начало цикла через 2 секунды (2000 миллисекунд)
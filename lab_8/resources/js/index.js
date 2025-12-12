// import _ from 'lodash';
// import './scss/styles.scss';
//import 'bootstrap';
import * as bootstrap from 'bootstrap';


// Данные для модалок с Popover'ами
const castleData = {
    vastseliina: {
        title: "Замок Вастселийна",
        content: `Поселение основано <span class="popover-text" data-bs-toggle="popover" data-bs-title="Дата основания" data-bs-content="Примерно 1273 год">около 1273 года</span> дерптским епископом на развалинах древнего чудского города Вастселийна, сам замок построен <span class="popover-text" data-bs-toggle="popover" data-bs-title="Строительство" data-bs-content="60 лет спустя после основания поселения">60 лет спустя</span> магистром <span class="popover-text" data-bs-toggle="popover" data-bs-title="Ливонский орден" data-bs-content="Рыцарский орден, действовавший в Прибалтике">Ливонского ордена</span> Бурхардом фон Дрейлебеном, после опустошительного набега псковичей на юго-восточную часть Лифляндии. Строительство крепости было завершено в <span class="popover-text" data-bs-toggle="popover" data-bs-title="Год завершения" data-bs-content="1342 год - окончание строительства">1342 году</span>.`
    },
    vasknarva: {
        title: "Замок Васкнарва",
        content: `Васкнарва под названием <span class="popover-text" data-bs-toggle="popover" data-bs-title="Немецкое название" data-bs-content="Neuschloss - 'Новый замок'">Нейшлосс</span> возникла в середине XIV века, когда рыцарями Ливонского ордена в <span class="popover-text" data-bs-toggle="popover" data-bs-title="Год основания" data-bs-content="1349 год - начало строительства">1349 году</span> была сооружена деревянная крепость, перестроенная позднее в <span class="popover-text" data-bs-toggle="popover" data-bs-title="Реконструкция" data-bs-content="1427 год - начало перестройки в камень">1427 году</span> в каменный замок Нойшлюс (Нейшлосс) (рус. Низлот). Строительство замка продолжалось <span class="popover-text" data-bs-toggle="popover" data-bs-title="Продолжительность" data-bs-content="15 лет строительства">15 лет</span> с 1427 по 1442 годы.<br><br>
                    Последним фогтом Васкнарвы был <span class="popover-text" data-bs-toggle="popover" data-bs-title="Последний правитель" data-bs-content="Дитрих фон дер Штайнкуль - последний фогт замка">Дитрих фон дер Штайнкуль</span>, потерявший свои владения, когда русские в ходе <span class="popover-text" data-bs-toggle="popover" data-bs-title="Исторический период" data-bs-content="Ливонская война (1558-1583)">Ливонской войны</span> в начале июня 1558 года захватили замок.`
    },
    koluvere: {
        title: "Замок Колувере (Лоде)",
        content: `Рыцарский замок существовал на этом месте с <span class="popover-text" data-bs-toggle="popover" data-bs-title="Период" data-bs-content="XIII век - время основания">XIII века</span>. С <span class="popover-text" data-bs-toggle="popover" data-bs-title="Принадлежность" data-bs-content="1439 год - переход во владение епископства">1439 года</span> он принадлежал <span class="popover-text" data-bs-toggle="popover" data-bs-title="Церковное владение" data-bs-content="Эзель-Викское епископство - церковное государство">Эзель-Викскому епископству</span>. Считается, что самой старой частью крепости может быть <span class="popover-text" data-bs-toggle="popover" data-bs-title="Архитектура" data-bs-content="Четырехгранная оборонительная башня">четырехгранная оборонительная башня</span>, датируемая началом XIV века.`
    },
    peide: {
        title: "Замок Пёйде (Вейсенштейн)",
        content: `Старейшей частью замка является <span class="popover-text" data-bs-toggle="popover" data-bs-title="Архитектурный элемент" data-bs-content="Донжон - главная башня в европейских феодальных замках">восьмигранная жилая башня-донжон</span>, высотой в <span class="popover-text" data-bs-toggle="popover" data-bs-title="Высота" data-bs-content="30 метров - значительная высота для того времени">30 м</span>, построенная в <span class="popover-text" data-bs-toggle="popover" data-bs-title="Период строительства" data-bs-content="1260-е годы">60-е годы XIII века</span>. Донжон имел <span class="popover-text" data-bs-toggle="popover" data-bs-title="Структура" data-bs-content="Шесть этажей с разным назначением">шесть этажей</span>, из которых три нижних были перекрыты сводами. Второй этаж был приспособлен для жилья, три верхних служили для военных целей.<br><br>
                    В начале XIV века к башне было пристроено здание конвента с замкнутым двором. Ещё позднее были построены наружная оборонительная стена, западная надвратная башня и северо-восточная <span class="popover-text" data-bs-toggle="popover" data-bs-title="Назначение" data-bs-content="Пороховая башня - хранилище боеприпасов">Пороховая башня</span>. В XVI веке замок был окружён земляными укреплениями — бастионами и валами.`
    },
    pyltsamaa: {
        title: "Замок Пылтсамаа (Оберпален)",
        content: `Замок был основан <span class="popover-text" data-bs-toggle="popover" data-bs-title="Организация" data-bs-content="Ливонский орден - католическая военно-религиозная организация">Ливонским орденом</span> в <span class="popover-text" data-bs-toggle="popover" data-bs-title="Год основания" data-bs-content="1272 год">1272 году</span> в качестве оборонительной крепости для крестоносцев. Во время <span class="popover-text" data-bs-toggle="popover" data-bs-title="Военный конфликт" data-bs-content="Ливонская война (1558-1583)">Ливонской войны</span> замок был оккупирован польскими войсками. В <span class="popover-text" data-bs-toggle="popover" data-bs-title="Период" data-bs-content="1570-1578 годы">1570—1578 годах</span> здание замка служило официальной резиденцией принца <span class="popover-text" data-bs-toggle="popover" data-bs-title="Историческая личность" data-bs-content="Магнус - датский принц, претендент на ливонский трон">Магнуса</span>, который вместе с царём <span class="popover-text" data-bs-toggle="popover" data-bs-title="Русский царь" data-bs-content="Иван IV Грозный">Иваном Грозным</span> стремился создать Ливонское королевство.`
    }
};
// Порядок замков для навигации
const castleOrder = ['vastseliina', 'vasknarva', 'koluvere', 'peide', 'pyltsamaa'];
let currentCastleIndex = 0;
let castleModal = null;

// Функция открытия модалки
function openModal(castleId) {
    const castleIndex = castleOrder.indexOf(castleId);
    if (castleIndex !== -1) {
        currentCastleIndex = castleIndex;

        // Создаем модалку только один раз
        if (!castleModal) {
            castleModal = new bootstrap.Modal(document.getElementById('castleModal'));
        }

        showCastleModal();
        castleModal.show();
    }
}

// Функция показа модалки с текущим замком
function showCastleModal() {
    const castleId = castleOrder[currentCastleIndex];
    const data = castleData[castleId];

    document.getElementById('modalTitle').textContent = data.title;
    document.getElementById('modalBody').innerHTML = `
                <p>${data.content}</p>
                <div class="text-center text-muted mt-3">
                    <small>Замок ${currentCastleIndex + 1} из ${castleOrder.length}</small>
                </div>
            `;

    // Инициализируем Popover'ы
    setTimeout(() => {
        initPopovers();
    }, 100);
}

// Функция навигации
function navigateCastles(direction) {
    if (direction === 'next') {
        currentCastleIndex = (currentCastleIndex + 1) % castleOrder.length;
    } else if (direction === 'prev') {
        currentCastleIndex = (currentCastleIndex - 1 + castleOrder.length) % castleOrder.length;
    }
    showCastleModal();
}

// Обработчик клавиатуры
function handleKeyboardNavigation(event) {
    // Проверяем, открыта ли модалка
    const modal = document.getElementById('castleModal');
    if (modal.classList.contains('show')) {
        switch (event.key) {
            case 'ArrowLeft':
                event.preventDefault();
                navigateCastles('prev');
                break;
            case 'ArrowRight':
                event.preventDefault();
                navigateCastles('next');
                break;
        }
    }
}

// Функция инициализации Popover'ов
function initPopovers() {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => {
        return new bootstrap.Popover(popoverTriggerEl, {
            trigger: 'hover focus',
            placement: 'top',
            delay: { "show": 100, "hide": 100 }
        });
    });
}

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', function () {
    initPopovers();

    // Добавляем обработчик клавиатуры
    document.addEventListener('keydown', handleKeyboardNavigation);

    // Toast для кнопки Загрузить
    const downloadBtn = document.getElementById('downloadBtn');
    const downloadToast = new bootstrap.Toast(document.getElementById('downloadToast'), {
        autohide: true,
        delay: 5000
    });

    downloadBtn.addEventListener('click', function () {
        downloadToast.show();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Находим кнопку и toast
    const downloadBtn = document.getElementById('downloadBtn');
    const downloadToast = document.getElementById('downloadToast');

    // Создаем экземпляр Toast
    const toast = new bootstrap.Toast(downloadToast, {
        autohide: true,      // Автоматически скрывать
        delay: 5000          // Показывать 5 секунд
    });

    // Обработчик клика на кнопку
    downloadBtn.addEventListener('click', function () {
        toast.show();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Вешаем обработчики на кнопки
    document.querySelectorAll('[data-castle-id]').forEach(button => {
        button.addEventListener('click', function() {
            const castleId = this.getAttribute('data-castle-id');
            openModal(castleId);
        });
    });
});
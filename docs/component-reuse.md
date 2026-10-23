# Границы переиспользуемых компонентов

Основание — U-27 от 23 октября 2026. Это описание реализации и контрактов дизайн-прототипа. Требование находится в `requirements.md`, а результат просмотра исходников — в [отдельном отчёте](reusability-review-2026-10-23.md). Прототип не становится renderer будущего Blade-каталога.

## Компонент и композиция

Компонент владеет повторяемой разметкой, стилями и взаимодействием; получает данные и слоты от вызывающего кода. У него нет знаний о клиентах, заказах, текущем маршруте или конкретных демонстрационных фотографиях.

Композиция экрана выбирает данные, тексты, действия, форматирование предметных значений и порядок компонентов. Поэтому `ordersScreen`, `customerScreen`, `editScreen`, `orderRows`, наборы примеров и адаптер `photoCard` остаются композициями. Они используют общие компоненты. Обёртки не скрывают дублирующую разметку Table/Tabs/Dialog/MediaCard.

Слот разметки — HTML, собранный доверенным renderer приложения. Строковые данные экранируются. В слоты `media`, `actions`, `header`, `body`, `content`, `intro`, `html` нельзя передавать необработанный пользовательский HTML. Будущая Blade-реализация должна сохранить разделение текстовых props и slots, не копируя JS API автоматически.

## Фактические точки входа прототипа

| Семейство | Общий источник / точка входа | Данные вызывающей композиции |
| --- | --- | --- |
| Icon, Button, Badge/Status, Avatar, Alert | `ui-components.js`: `components.icon/button/badge/avatar/message` | Имя Lucide, текст, тон, вариант, действие/URL, инициалы |
| Slider / Progress | `ui-components.js`: `components.slider/progress`; Slider — общий enhance в `controls.js` | ID, подпись, value и границы; шаг/disabled ползунка либо неопределённое состояние прогресса |
| Field, Input, Select, Textarea | `ui-components.js`: `components.field/input/select/textarea` | ID, имя, тип, значение, варианты, label/hint/error, атрибуты |
| Select, поиск, число, пароль, цвет | `controls.js`: `controls.enhance(root)` | Нативные контролы внутри переданной области; взаимодействие не зависит от ID страницы |
| Calendar / TimePicker | `date-time-controls.js`: `dateTimeControls.enhance(root)` через общий enhance | Нативный input даты/времени, его value/min/max/step/disabled |
| Popover / ContextMenu | `ui-components.js`: `components.popover/contextMenu`; `overlays.js` | ID, подписи, доверенный контент, пункты, действия; точка открытия контекстного меню |
| ActionMenu / Tooltip | `ui-components.js`: `components.actionMenu`; `overlays.js` — общий runtime | ID, пункты и действия; текст подсказки — атрибут владельца |
| Fieldset и выборы | Нативные fieldset/legend, input checkbox/radio/switch и общий `controls.css` | Имена/значения, checked/disabled и подписи; отдельного page-specific JS нет |
| Table / Pagination | `ui-components.js`: `components.table/pagination` | Заголовок и строки слотами, подпись таблицы; ID пагинации и числа страницы |
| Accordion / Collapsible | `ui-components.js`: `components.accordion/collapsible`; `ui-runtime.js` | ID, заголовки, доверенное содержимое, начальное раскрытие, режим группы |
| Tabs | `ui-components.js`: `components.tabs`; `ui-runtime.js` | Уникальный ID группы, подпись, список значений/заголовков/панелей, активное значение |
| Navigation / Breadcrumbs / AppShell | `ui-components.js`: `components.navigationItems/breadcrumbs/appShell` | Дерево пунктов, current/open, ссылки, слоты оболочки |
| PageHeader / Section / FormSection / Panel | `ui-components.js`: одноимённые renderers | Заголовки, описания, уровень композиции, содержимое и действия |
| DescriptionList / Timeline | `ui-components.js`: `components.descriptionList/timeline` | Массив пар характеристик или событий |
| Empty / Loading / Skeleton / Error / ValidationSummary | `ui-components.js`: `components.empty/loading/skeleton/loadingSkeleton/errorState/validationSummary` | Тексты, действия, вариант скелетона либо список ошибок |
| Sheet / Drawer | `ui-components.js`: `components.sheetContent`; `ui-runtime.js`: `panels.show` поверх общего Dialog | Dialog-элемент, сторона, заголовок/описание, слоты body/actions, lifecycle callbacks |
| Dialog | `ui-components.js`: `components.dialogContent`; `ui-runtime.js`: `dialogs.show(element, options)` | Элемент native dialog, заголовок, тело, действия, onMount/onCleanup |
| MediaCard | `media-card.js` и `media-card.css`: `mediaCard(options)` | Изображение и действия слотами, заголовок, описание, бейдж, уровень заголовка |
| Image / Gallery / ImageSlider / ImageViewer | `media-components.js`: `mediaComponents.image/gallery/slider/openViewer` | Собственный массив изображений каждого экземпляра, подписи, выбранный индекс и label |
| Carousel / карточная сетка | `media-components.js`: `mediaComponents.carousel/grid` | Готовое содержимое, в том числе MediaCard; контейнер не рендерит карточку самостоятельно |
| ImageFigure / ImageBlock / состояния медиа | `media-components.js`: `mediaComponents.figure/imageBlock/mediaState` | Изображение, подписи, текст, действия и состояние |

Все `components.*` и `mediaComponents.*` принадлежат `window.AesteticReference`. Общие стили прототипа лежат в `styles.css`, `controls.css`, `date-time-controls.css`, `overlays.css`, `media.css` и `media-card.css`. Классы и значения тем не копируются в примеры.

## Изоляция взаимодействий

- Tabs выбирает кнопки и панели внутри своей группы. Клавиатура использует фактические элементы DOM. Изменение сообщает событие `ui:tab-change`; только код приложения связывает конкретную группу с состоянием клиента.
- Pagination несёт свой `data-ui-pagination`; приложение обрабатывает только ID списка, за который отвечает. Компонент не меняет заказы сам.
- Gallery и ImageSlider хранят данные своего набора в ближайшем `data-media-items`. При открытии просмотра берётся этот набор. Стрелки, Home/End и счётчики используют фактическое число слайдов экземпляра.
- `openViewer({items, selected, title, element})` может использовать переданный dialog; общий host в прототипе отмечен `data-image-viewer`.
- Dialog хранит opener и cleanup отдельно для каждого элемента. Обычные диалоги и просмотр изображений используют тот же renderer и управление фокусом.
- `mediaComponents.enhance(root)` не добавляет повторные подписки уже инициализированной карусели. `destroy(root)` отключает её ResizeObserver и scroll-handler; перед заменой содержимого и при закрытии диалога вызывается очистка.
- В AppShell предполагается один главный `main` на странице; фиксированный ID для skip-link относится к этому контракту оболочки.

## Где смотреть

**Параметры макета → Композиции компонентов**: те же Breadcrumbs, Navigation, Table, Field/FormSection и две независимые группы Tabs на других данных. **Галереи и изображения**: единые MediaCard, Gallery, Slider, Carousel, Figure/Block, состояния и два дополнительных слайдера с разным числом фотографий. **Поля и выбор**: семейство контролов, ActionMenu, Tooltip и сообщения.

Эти панели рендерят компоненты прототипа. Будущий живой каталог должен рендерить реальные Blade-компоненты, как требуют AGENTS.md и архитектура проекта. Библиотечные статусы не меняются от наличия прототипных renderers.

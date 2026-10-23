# Каталог компонентов

Таблицы ниже описывают целевой состав семейств Aestetic UI. Написаны 14 атомарных контрактов и Scope/Page/Assets со статусом experimental; остальные семейства planned. [Живой раздел «Компоненты»](../workbench/README.md) показывает группировку по задачам, правила UI/UX, подключение, состояния и API из [общего реестра](../catalog/components.json). Новые атомы не прошли HTTP/визуальную приёмку; опубликованного выпуска нет. [Состав и ограничения](atomic-catalog.md).

Публичные имена и параметры фиксируются при реализации. Табличные части и label/hint/error могут быть внутренними элементами семейства, а не отдельными независимыми компонентами.

Атом решает одну функцию; сборный компонент связывает элементы; структурный компонент владеет расположением/навигацией; паттерн описывает законченный сценарий. Число DOM-узлов не определяет атомарность.

Машинный источник: [catalog/components.json](../catalog/components.json). Колонка 0.1 — исходная граница пилота; «далее» сохраняет расширение в дорожной карте. Семейства могут реализовываться по частям, со статусом конкретного контракта.

## Атомарные элементы

| ID / имя | Назначение | Очередь | Состав / зависимости |
| --- | --- | --- | --- |
| `icon` / Icon | Только оригинальная иконка Lucide с согласованной семантикой доступности (U-11) | 0.1 | — |
| `button` / Button | Действие, в том числе loading/disabled и icon-only | 0.1 | `icon`, `spinner` |
| `link` / Link | Переход по готовому URL | 0.1 | — |
| `spinner` / Spinner | Индикация выполнения | 0.1 | — |
| `status` / Status | Бейдж состояния с понятным текстом, смысловым цветом и необязательной иконкой Lucide; [правила](badges.md) | 0.1 | `icon` |
| `input` / Input | Собственное оформление ввода с нативной семантикой поддерживаемого типа | 0.1 | — |
| `textarea` / Textarea | Многострочный ввод в общем оформлении полей | 0.1 | — |
| `select` / Select | Собственный single/multiple выбор с оформленным списком и нативным значением формы | 0.1 | — |
| `checkbox` / Checkbox | Независимый выбор | 0.1 | — |
| `radio` / Radio | Один вариант в группе | 0.1 | — |
| `slider` / Slider | Один числовой параметр с настраиваемым шагом; [правила](slider-progress.md) | далее | — |
| `progress` / Progress | Доля выполнения либо неопределённое ожидание; [правила](slider-progress.md) | далее | — |
| `switch` / Switch | Значение переключателя без сетевого запроса | 0.1 | — |
| `file-input` / FileInput | Нативный выбор файла без storage CRUD | 0.1 | — |
| `avatar` / Avatar | Малое изображение пользователя и fallback, группы с перекрытием и счётчиком; [правила](avatars.md) | далее | — |
| `thumbnail` / Thumbnail | Превью изображения и fallback | далее | — |

## Сборные компоненты

| ID / имя | Назначение | Очередь | Состав / зависимости |
| --- | --- | --- | --- |
| `field` / Field | Подпись, контрол, hint/error и ARIA-связи | 0.1 | — |
| `fieldset` / Fieldset | Группа выборов с legend | 0.1 | `field` |
| `input-group` / InputGroup | Контрол с дополнением или кнопкой | 0.1 | `field`, `button` |
| `range` / Range | Пара связанных полей от/до | 0.1 | `field`, `input` |
| `form` / Form | Нативная форма и явная Laravel-интеграция | 0.1 | — |
| `form-actions` / FormActions | Главное и дополнительные действия формы | 0.1 | `button`, `link` |
| `validation-summary` / ValidationSummary | Общий список ошибок формы | 0.1 | `link` |
| `panel` / Panel | Выделенная рабочая секция с actions/footer | 0.1 | — |
| `media-card` / MediaCard | Самостоятельная карточка с изображением, заголовком, необязательными бейджем, описанием и действиями; отдельно, в сетке и в карусели; [правила](media.md) | далее | `thumbnail`, `status`, `button` |
| `alert` / Alert | Пояснение результата, ошибки или предупреждения | 0.1 | `icon` |
| `empty-state` / EmptyState | Причина пустоты и следующий шаг | 0.1 | `icon`, `button` |
| `description-list` / DescriptionList | Характеристики объекта в dl/dt/dd | 0.1 | — |
| `sheet` / Sheet | Боковое модальное окно слева/справа; [правила](panels.md) | далее | `dialog`, `button`, `icon` |
| `drawer` / Drawer | Нижняя выдвижная панель; [правила](panels.md) | далее | `dialog`, `button`, `icon` |
| `popover` / Popover | Компактная немодальная поверхность рядом с элементом; [правила](overlays.md) | далее | `button`, `icon` |
| `context-menu` / ContextMenu | Контекстные действия области с клавиатурным и кнопочным вызовом; [правила](overlays.md) | далее | `button`, `icon` |
| `dialog` / Dialog | Модальная область с focus/close контрактом | 0.1 | `button` |
| `confirm-dialog` / ConfirmDialog | Подтверждение действия | 0.1 | `dialog`, `form-actions` |
| `help-dialog` / HelpDialog | Справка по действию или разделу | 0.1 | `dialog`, `button` |
| `dropdown` / Dropdown | Раскрывающаяся область с локальным состоянием | 0.1 | `button` |
| `action-menu` / ActionMenu | Меню действий с keyboard-контрактом | 0.1 | `dropdown`, `button`, `link` |
| `tabs` / Tabs | Локальные представления одной сущности | 0.1 | `button` |
| `pagination` / Pagination | Навигация по страницам результата | 0.1 | `link`, `icon` |
| `breadcrumbs` / Breadcrumbs | Положение в иерархии и переходы к родителям | 0.1 | `link`, `icon` |
| `accordion` / Accordion | Связанные раскрывающиеся разделы; [правила](disclosures.md) | далее | `icon` |
| `collapsible` / Collapsible | Самостоятельная дополнительная область; [правила](disclosures.md) | далее | `icon` |

## Структура, навигация и списки

| ID / имя | Назначение | Очередь | Состав / зависимости |
| --- | --- | --- | --- |
| `scope` / Scope | Тема, плотность и ограниченная область оформления | 0.1 | — |
| `assets` / Assets | Подключение согласованных CSS/JS | 0.1 | — |
| `page` / Page | Рабочая область и общий вертикальный ритм | 0.1 | `scope` |
| `page-header` / PageHeader | Заголовок страницы, пояснение и действия | 0.1 | `button` |
| `section` / Section | Смысловой раздел и его заголовок | 0.1 | — |
| `stack` / Stack | Последовательность элементов с семантическим интервалом | 0.1 | — |
| `inline` / Inline | Ряд элементов с переносом и согласованным промежутком | 0.1 | — |
| `field-group` / FieldGroup | Связанные поля и интервал внутри группы | 0.1 | `field` |
| `form-section` / FormSection | Секция формы с пояснением и группами | 0.1 | `section`, `field-group` |
| `page-layout` / PageLayout | Основная область и дополнительная панель | 0.1 | `page` |
| `app-shell` / AppShell | Общая оболочка с местами бренда, меню и аккаунта | 0.1 | `scope`, `navigation`, `page-layout` |
| `navigation` / Navigation | Глобальное дерево разрешённых разделов | 0.1 | `navigation-group`, `navigation-item` |
| `navigation-group` / NavigationGroup | Группировка и раскрытие разделов | 0.1 | `navigation-item` |
| `navigation-item` / NavigationItem | Пункт с active/expanded состоянием | 0.1 | `link`, `icon` |
| `list-toolbar` / ListToolbar | Итоги списка, размер страницы, действия | 0.1 | `page-size`, `button` |
| `filter-bar` / FilterBar | Фильтрующая форма из обычных полей | 0.1 | `form`, `field`, `form-actions` |
| `page-size` / PageSize | Количество строк на странице | 0.1 | `select` |
| `table` / Table | Семейство head/body/row/cell/sort/empty и overflow | 0.1 | `sort-header`, `table-empty-row` |
| `sort-header` / SortHeader | Заголовок с направлением и URL сортировки | 0.1 | `link`, `icon` |
| `selection` / Selection | Выбор видимых строк, select-all и indeterminate | 0.1 | `checkbox` |
| `bulk-actions` / BulkActions | Выбранное количество и отдельная форма действий | 0.1 | `selection`, `form-actions` |
| `table-empty-row` / TableEmptyRow | Пустая строка с корректным colspan | 0.1 | `empty-state` |

## Композиции страниц

| ID / имя | Назначение | Очередь | Состав / зависимости |
| --- | --- | --- | --- |
| `list-page` / ListPage | Готовая композиция списка с фильтрами и состояниями | 0.1 | `page`, `page-header`, `filter-bar`, `list-toolbar`, `table`, `pagination`, `bulk-actions` |
| `detail-page` / DetailPage | Карточка объекта с характеристиками и связанными данными | 0.1 | `page`, `page-header`, `description-list`, `tabs`, `section` |
| `edit-page` / EditPage | Форма редактирования с секциями и обратной связью | 0.1 | `page`, `page-header`, `form`, `form-section`, `validation-summary`, `form-actions` |
| `settings-page` / SettingsPage | Настройки с группами и локальной навигацией | далее | `edit-page`, `tabs` |
| `master-detail` / MasterDetail | Список и выбранный объект рядом | далее | `page-layout`, `list-page`, `detail-page` |
| `dashboard` / Dashboard | Обзор состояния и переходы к задачам | далее | `page`, `section`, `panel` |
| `sign-in` / SignIn | Вход и ошибки авторизации без backend auth | далее | `form`, `field`, `alert`, `form-actions` |

## Дополнительные модули

| ID / имя | Назначение | Очередь | Состав / зависимости |
| --- | --- | --- | --- |
| `combobox` / Combobox | Поиск и выбор варианта | далее | `input`, `select` |
| `tree-select` / TreeSelect | Выбор из переданной иерархии | далее | `select` |
| `image-picker` / ImagePicker | Выбор, локальное preview, replace/remove | далее | `file-input`, `button` |
| `media-grid` / MediaGrid | Превью, выбор и слоты действий | далее | `thumbnail`, `selection` |
| `image-figure` / ImageFigure | Изображение с alt, подписью, пропорциями и состояниями; [правила](media.md) | далее | `thumbnail` |
| `gallery` / Gallery | Сетка фотографий одного объекта с открытием просмотра | далее | `thumbnail`, `image-viewer` |
| `image-slider` / ImageSlider | Крупное изображение, подпись, стрелки, счётчик и миниатюры | далее | `image-figure`, `button`, `thumbnail` |
| `carousel` / Carousel | Горизонтальная подборка готового содержимого, включая MediaCard; слайды, кнопки и свайп | далее | `button` |
| `image-viewer` / ImageViewer | Модальный просмотр фотографий без обрезки | далее | `dialog`, `image-slider` |
| `image-block` / ImageBlock | Адаптивная композиция изображения и текста с действием | далее | `image-figure`, `panel`, `button` |
| `repeater` / Repeater | Добавление и удаление повторяющихся групп | далее | `field-group`, `button` |
| `rich-text` / RichText | Подключаемый текстовый редактор | далее | `field` |
| `key-value-tree` / KeyValueTree | Просмотр вложенных данных | далее | `description-list` |
| `command-palette` / CommandPalette | Быстрый поиск разрешённых команд и переходов | далее | `dialog`, `input`, `link` |

## Что описывать для каждого реализованного элемента

Медиасемейства добавлены по U-24. Примеры в [дизайн-прототипе](../design/reference/README.md) не меняют их статуса `planned`; [назначение и поведение](media.md) отделены от будущего Blade API. Gallery показывает фото объекта; MediaGrid предназначен для управления файлами. Карусель карточек не подменяет галерею ракурсов.

- Когда использовать и какие альтернативы выбрать в другом сценарии.
- Поддерживаемые props, defaults, slots, HTML/data/ARIA атрибуты.
- Состояния, клавиатуру, фокус, отсутствие данных, длинные подписи и ошибки.
- Владелец отступов, уровень заголовка и правила вложенности.
- Примеры реального Blade, темы, плотности и адаптивности.
- Версию появления, статус, зависимости и путь реализации.

## Как выбрать компонент

[Общие renderers прототипа и точки входа](component-reuse.md) показывают фактические места переиспользования. Метаданные `prototype` в JSON описывают только дизайн-прототип, не доступность Blade API. [Статическая проверка](reusability-review-2026-10-23.md) отделена от браузерной приёмки и библиотечного статуса.

- Основные разделы системы — Navigation; представления одного объекта — Tabs.
- Переход — Link; действие — Button; набор локальных команд — ActionMenu.
- Обычный смысловой раздел — Section; выделенная рабочая поверхность с действиями — Panel.
- Одно поле — Field; группа выборов — Fieldset; несколько связанных полей — FieldGroup; часть формы — FormSection.
- Характеристики одного объекта — DescriptionList; сопоставимые записи — Table.
- Подтверждение — ConfirmDialog; справка — HelpDialog; отправка/удаление остаётся у приложения.

Это исходные правила выбора. Их уточнение на эталонных экранах должно попасть в общий реестр правил для человека и Codex.

## Покрытие сложных административных сценариев

[Анализ МТС-33](mts-33-admin-reference.md) сопоставляет существующие семейства с конструкторами, модерацией, файлами, аналитикой и связанными журналами. В нём отдельно описаны предложения Disclosure, Metric, ChartPanel, MediaPreview/FileItem, CodeBlock и композиции ReviewPage, StructuredEditor, DiagnosticDetail. Они ещё не включены в машинный каталог и не являются API или частью утверждённого состава 0.1. Уже запланированные Repeater, RichText и медиаблоки получают предметные сценарии для будущей реализации.

## Оформление полей

[Поля ввода и выбор](form-controls.md) описывают направление U-15/U-18. Input, Textarea, одиночный Select, Checkbox/Radio/Switch и FileInput написаны как experimental Blade-компоненты. Field/Form и специализированные Calendar/TimePicker остаются planned; прототип не выдаётся за готовую Blade-реализацию этих семейств.

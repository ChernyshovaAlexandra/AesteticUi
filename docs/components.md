# Каталог компонентов

Исходная карта семейств Aestetic UI. Все записи ниже **planned**: это план разработки, а не существующий Blade API. Публичные имена и параметры фиксируются при реализации. Табличные части и label/hint/error могут быть внутренними элементами семейства, а не отдельными независимыми компонентами.

Атом решает одну функцию; сборный компонент связывает элементы; структурный компонент владеет расположением/навигацией; паттерн описывает законченный сценарий. Число DOM-узлов не определяет атомарность.

Машинный источник: [catalog/components.json](../catalog/components.json). Колонка 0.1 — исходная граница пилота; «далее» сохраняет расширение в дорожной карте. Семейства могут реализовываться по частям, со статусом конкретного контракта.

## Атомарные элементы

| ID / имя | Назначение | Очередь | Состав / зависимости |
| --- | --- | --- | --- |
| `icon` / Icon | Иконка с согласованной семантикой доступности | 0.1 | — |
| `button` / Button | Действие, в том числе loading/disabled и icon-only | 0.1 | `icon`, `spinner` |
| `link` / Link | Переход по готовому URL | 0.1 | — |
| `spinner` / Spinner | Индикация выполнения | 0.1 | — |
| `status` / Status | Текстовое состояние с необязательным индикатором | 0.1 | `icon` |
| `input` / Input | Нативный ввод поддерживаемого типа | 0.1 | — |
| `textarea` / Textarea | Многострочный ввод | 0.1 | — |
| `select` / Select | Нативный single/multiple выбор | 0.1 | — |
| `checkbox` / Checkbox | Независимый выбор | 0.1 | — |
| `radio` / Radio | Один вариант в группе | 0.1 | — |
| `switch` / Switch | Значение переключателя без сетевого запроса | 0.1 | — |
| `file-input` / FileInput | Нативный выбор файла без storage CRUD | 0.1 | — |
| `avatar` / Avatar | Малое изображение пользователя и fallback | далее | — |
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
| `alert` / Alert | Пояснение результата, ошибки или предупреждения | 0.1 | `icon` |
| `empty-state` / EmptyState | Причина пустоты и следующий шаг | 0.1 | `icon`, `button` |
| `description-list` / DescriptionList | Характеристики объекта в dl/dt/dd | 0.1 | — |
| `dialog` / Dialog | Модальная область с focus/close контрактом | 0.1 | `button` |
| `confirm-dialog` / ConfirmDialog | Подтверждение действия | 0.1 | `dialog`, `form-actions` |
| `help-dialog` / HelpDialog | Справка по действию или разделу | 0.1 | `dialog`, `button` |
| `dropdown` / Dropdown | Раскрывающаяся область с локальным состоянием | 0.1 | `button` |
| `action-menu` / ActionMenu | Меню действий с keyboard-контрактом | 0.1 | `dropdown`, `button`, `link` |
| `tabs` / Tabs | Локальные представления одной сущности | 0.1 | `button` |
| `pagination` / Pagination | Навигация по страницам результата | 0.1 | `link`, `icon` |
| `breadcrumbs` / Breadcrumbs | Положение в иерархии и переходы к родителям | 0.1 | `link`, `icon` |

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
| `repeater` / Repeater | Добавление и удаление повторяющихся групп | далее | `field-group`, `button` |
| `rich-text` / RichText | Подключаемый текстовый редактор | далее | `field` |
| `key-value-tree` / KeyValueTree | Просмотр вложенных данных | далее | `description-list` |
| `command-palette` / CommandPalette | Быстрый поиск разрешённых команд и переходов | далее | `dialog`, `input`, `link` |

## Что описывать для каждого реализованного элемента

- Когда использовать и какие альтернативы выбрать в другом сценарии.
- Поддерживаемые props, defaults, slots, HTML/data/ARIA атрибуты.
- Состояния, клавиатуру, фокус, отсутствие данных, длинные подписи и ошибки.
- Владелец отступов, уровень заголовка и правила вложенности.
- Примеры реального Blade, темы, плотности и адаптивности.
- Версию появления, статус, зависимости и путь реализации.

## Как выбрать компонент

- Основные разделы системы — Navigation; представления одного объекта — Tabs.
- Переход — Link; действие — Button; набор локальных команд — ActionMenu.
- Обычный смысловой раздел — Section; выделенная рабочая поверхность с действиями — Panel.
- Одно поле — Field; группа выборов — Fieldset; несколько связанных полей — FieldGroup; часть формы — FormSection.
- Характеристики одного объекта — DescriptionList; сопоставимые записи — Table.
- Подтверждение — ConfirmDialog; справка — HelpDialog; отправка/удаление остаётся у приложения.

Это исходные правила выбора. Их уточнение на эталонных экранах должно попасть в общий реестр правил для человека и Codex.

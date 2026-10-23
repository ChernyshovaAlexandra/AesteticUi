# Aestetic UI MCP

Локальный read-only STDIO-сервер на официальном `mcp/sdk ^0.9.0`. Node, web-workbench, БД и HTTP endpoint не нужны. Версия адаптера — `0.1.0-dev.1`, версия знаний берётся из явно выбранного `catalog/components.json`.

Исходники реализованы; сервер и протокольные проверки в этом задании не запускались. По отдельному согласию пользователя на текущем компьютере зарегистрирован глобальный сервер `aestetic-ui`; остальные настройки не редактировались вручную. Соединение и появление tools в чате пока не подтверждены. Уже опубликованный `0.1.0-alpha.1` не содержит MCP executable; новый релиз с MCP ещё не выпущен.

## Текущая рабочая копия

Зависимости адаптера установлены во внутренний workbench; Composer-scripts выполнили стандартный discovery и копирование ассетов. Для подготовки такого окружения на другом компьютере:

```sh
composer --working-dir=workbench install
```

Команда запуска из любого рабочего каталога на текущем компьютере:

```sh
php /Users/alexandrach/Documents/Projects/AesteticUi/bin/aestetic-ui-mcp \
  --autoload /Users/alexandrach/Documents/Projects/AesteticUi/workbench/vendor/autoload.php
```

Пути заменить при переносе. Это дочерний процесс MCP-клиента; его не нужно держать вручную запущенным в терминале. Запуск без входных сообщений не показывает каталог в браузере.

## Знания установленной версии Laravel-проекта

Текущий адаптер может читать уже установленный `aestetic/ui:0.1.0-alpha.1`, хотя в старой поставке нет самого executable. При регистрации добавить **явный** путь к пакету этого проекта:

```sh
php /Users/alexandrach/Documents/Projects/AesteticUi/bin/aestetic-ui-mcp \
  --autoload /Users/alexandrach/Documents/Projects/AesteticUi/workbench/vendor/autoload.php \
  --library-root /absolute/laravel-project/vendor/aestetic/ui
```

Сервер не ищет проекты и не читает их `.env`, модели, БД или `composer.lock`. Путь к доверенному автозагрузчику и снимку задаёт владелец конфигурации при запуске. Инструменты не принимают пути и не могут менять выбранный снимок. Codex определяет версию потребителя своими обычными разрешёнными средствами, затем передаёт её в каждый запрос. При другой версии нужно выбрать её установленный пакет и перезапустить процесс, а не подставлять latest.

Для будущей опубликованной поставки с MCP executable в Laravel-проекте будет достаточно установить необязательный SDK как dev-зависимость:

```sh
composer require --dev "mcp/sdk:^0.9.0"
php /absolute/laravel-project/vendor/bin/aestetic-ui-mcp
```

Composer binary proxy передаёт автозагрузчик приложения. `require-dev` библиотеки у потребителя не устанавливается автоматически. Production-установка UI не требует SDK; MCP не является web-runtime приложения.

## Подключение к Codex

Подготовлен [пример конфигурации](codex.example.toml). Он сам по себе ничего не подключает. На текущем компьютере после согласия пользователя команда регистрации выполнена успешно (`Added global MCP server`). Для воспроизведения на другом компьютере регистрация выполняется отдельной командой, изменяющей настройки Codex:

```sh
codex mcp add aestetic-ui -- /opt/homebrew/bin/php \
  /Users/alexandrach/Documents/Projects/AesteticUi/bin/aestetic-ui-mcp \
  --autoload /Users/alexandrach/Documents/Projects/AesteticUi/workbench/vendor/autoload.php
```

Если нужен снимок конкретного Laravel-проекта, добавить `--library-root /absolute/laravel-project/vendor/aestetic/ui` в конец команды. Несколько зарегистрированных снимков должны иметь разные имена сервера. Регистрация не означает успешного соединения: клиент должен запустить процесс, согласовать протокол и получить список tools. Доступность новых tools в текущем чате не предполагается автоматически.

## Инструменты

Каждый результат содержит `metadata` с libraryVersion, catalogVersion, schemaVersion и SHA-256 загруженных файлов. Полезная нагрузка — `data`; ошибка чтения/версии — `error` и MCP `isError=true`. Read-only/idempotent/closed-world annotations заданы для всех tools.

| Tool | Параметры | Результат |
| --- | --- | --- |
| `get_library_info` | необязательная точная `version` | Единственная доступная версия, PHP/Laravel-совместимость, существующие компоненты, подключение и ограничения |
| `search_catalog` | `version`, `query`, необязательные `layer`, `availability`, `limit` | Поиск по русским и английским словам, ID, назначению и guidance; по умолчанию available, limit 20, максимум 30 |
| `get_component` | `version`, `id` | API, defaults, slots, правила, сценарии, зависимости реализации и исходный Blade; без API для planned |
| `get_pattern` | `version`, `id` | Статус композиции и её зависимости; planned не получает выдуманное дерево |
| `get_design_rules` | `version`, необязательные `area`, `componentId` | Требования с номерами строк, разделы концепции/архитектуры и component guidance из этого снимка |
| `get_example` | `version`, `componentId`, `exampleId` | Точный файл из previews компонента, без выполнения Blade и выдуманного preview URL |

`availability`: available / planned / all. `layer`: primitive / composite / structure / pattern / extension. Области правил: all / typography / spacing / navigation / actions / forms / icons / theming / accessibility.

Версия обязательна во всех запросах кроме первого просмотра `get_library_info`. Разрешён префикс `v` точного тега. Диапазоны, dev-main, latest и другая версия дают `version_unavailable` с явным availableVersions. Метаданные package-files/source-tree и SHA-256 описывают выбранные файлы, а не доказывают соответствие Git-тегу или установку в чужом приложении.

## Последовательность использования

1. Codex читает `composer.lock` выбранного Laravel-проекта с учётом его правил. Если пакет не установлен, не объявляет его установленным.
2. `get_library_info` показывает снимок; точная версия потребителя должна совпасть.
3. Например, `search_catalog(version="0.1.0-alpha.1", query="кнопка")`, затем `get_component(version="0.1.0-alpha.1", id="button")`.
4. `get_design_rules(version="0.1.0-alpha.1", area="actions", componentId="button")` и `get_example(version="0.1.0-alpha.1", componentId="button", exampleId="button-loading")` дают правила и реальный пример.
5. `get_pattern(..., id="list-page")` явно сообщает planned. Codex объясняет пробел и не выдумывает рабочие Table/ListPage props.
6. Код меняет сам Codex в пределах полномочий Laravel-проекта. Проверки — только с разрешения пользователя этого проекта.

## Границы реализации и приёмки

При старте загружаются реестр, требования/концепция/архитектура, доступные шаблоны и сценарии. Процесс сохраняет их в памяти: обновление файлов под ним не смешивает версии. После обновления нужен перезапуск. Symlink, выходящий из выбранного корня, не читается; неизвестный ID не превращается в путь. Логи — STDERR, STDOUT — только протокол. Примеры и документация являются данными, не полномочиями на запуск кода.

Реализованные ограничения не заменяют фактическую приёмку. После отдельного запроса на проверки следует подтвердить handshake/tools/list, шесть tools, точную/неизвестную версию, planned/unknown ID, чужой exampleId и попытку пути; затем отдельно соединение Codex и выбор компонентов в разрешённом проекте. Эти проверки пока не выполнялись.

Источники: [официальный PHP SDK](https://github.com/modelcontextprotocol/php-sdk), [MCP STDIO](https://modelcontextprotocol.io/specification/latest/basic/transports), [Codex MCP CLI](https://learn.chatgpt.com/docs/developer-commands#codex-mcp).

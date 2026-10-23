# Установка и подключение

## Текущая граница

Composer-пакет `aestetic/ui` содержит 14 атомов и Scope/Page/Assets. Совместимость первого alpha-выпуска: PHP `^8.3` и Laravel 12. Внутренний workbench использует Laravel 12.70.1. Другие версии Laravel пока не заявлены.

Версия [`0.1.0-alpha.1`](https://packagist.org/packages/aestetic/ui#v0.1.0-alpha.1) опубликована в Packagist 23 октября 2026. [GitHub-релиз](https://github.com/ChernyshovaAlexandra/AesteticUi/releases/tag/v0.1.0-alpha.1), лицензия MIT. Реестр описывает `libraryVersion=0.1.0-alpha.1`; контракты остаются experimental.

Проверены локальное подключение, установка ZIP и установка опубликованной версии из Packagist через Composer без symlink: discovery, копирование CSS/JS и 175 успешных запросов рендеринга. Полная визуальная приёмка и проверка браузерных взаимодействий не завершены; существующие внешние Laravel-проекты не изменялись. [Подробности подготовки](first-release.md).

## Установка первого выпуска из Packagist

Из корня Laravel-приложения:

```sh
composer require "aestetic/ui:0.1.0-alpha.1"
php artisan vendor:publish --tag=aestetic-ui-assets
```

Указанная точная alpha-версия устанавливается без глобального снижения `minimum-stability`. В alpha API может меняться, поэтому обновлять ограничение версии следует осознанно. Дополнительный `repositories` после регистрации в Packagist не нужен. Старую локальную path-запись для `aestetic/ui` нужно убрать при переходе на публичную поставку.

## Локальное подключение до публикации

В `composer.json` выбранного Laravel-приложения добавить запись в существующий массив `repositories`, сохранив остальные настройки:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/Users/alexandrach/Documents/Projects/AesteticUi",
            "options": {
                "symlink": true,
                "versions": {
                    "aestetic/ui": "0.1.x-dev"
                }
            }
        }
    ]
}
```

Путь заменить при переносе на другой компьютер. Это ссылка на единственное текущее дерево библиотеки, без создания отдельной копии или worktree. `versions` задаёт версию только для локального path-источника и не добавляет релизную версию в библиотеку.

Из корня приложения:

```sh
composer require "aestetic/ui:0.1.x-dev"
php artisan vendor:publish --tag=aestetic-ui-assets
```

Laravel обнаруживает `Aestetic\Ui\AesteticUiServiceProvider` через `extra.laravel.providers`. Если в приложении отключён discovery для этого пакета или всех пакетов, provider нужно явно добавить в существующий список `bootstrap/providers.php`:

```php
Aestetic\Ui\AesteticUiServiceProvider::class,
```

Публикация ассетов копирует `dist/` в `public/vendor/aestetic-ui/`. Это локальное копирование файлов внутри приложения, а не публикация библиотеки в интернете. CSS уже включён в исходники поставки; Node, Tailwind, CDN и сборка на стороне потребителя не нужны. Данные БД, маршруты, авторизация и модели приложение определяет самостоятельно.

## Первый Blade-шаблон

Полный пример находится в [examples/getting-started.blade.php](../examples/getting-started.blade.php). Его можно перенести в `resources/views` выбранного приложения и подключить обычным маршрутом этого приложения. Маршруты пакет не добавляет.

Минимальная композиция для существующего layout:

```blade
{{-- В head основного layout --}}
<x-ui::assets />

{{-- В body; если layout уже содержит main, не вкладывать в него второй Page --}}
<x-ui::scope theme="system" density="comfortable">
    <x-ui::page title="Настройки">
        <x-slot:description>Параметры приложения.</x-slot:description>

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')
            {{-- Поля и маршрут предоставляет приложение. --}}
            <x-ui::button type="submit">Сохранить</x-ui::button>
        </form>
    </x-ui::page>
</x-ui::scope>
```

`settings.update` — условный маршрут приложения, пакет его не создаёт. Для примера без бизнес-маршрутов использовать готовый `examples/getting-started.blade.php`.

## Реализованные контракты

Версионированный источник API — [catalog/components.json](../catalog/components.json). 17 контрактов имеют статус experimental; опубликованного выпуска пока нет. Полный каталог, правила UI/UX и параметры находятся в разделе «Компоненты» workbench. Ниже — краткий пример базовой композиции, не полный перечень атомов.

| Компонент | API текущего каркаса | Состояния и выбор |
| --- | --- | --- |
| `<x-ui::assets />` | Без props; CSS выбирается по пакетному `dist/manifest.json` | Разместить в head; `@once` исключает повторный link в одном Blade-render |
| `<x-ui::scope>` | `theme`: light/dark/system; `density`: comfortable/compact; default slot | Область токенов и типографики. Props по умолчанию берутся из конфига; system следует настройкам ОС через CSS, без JS |
| `<x-ui::page>` | Необязательный `title`; slots default/description/actions | Создаёт main и при наличии title — h1; владеет отступами страницы и заголовка. Один main, без вложенных Page |
| `<x-ui::button>` | `variant`: primary/outline; `type`: button/submit/reset, default button; `disabled`: boolean; default slot | Только действие с текстовой подписью. Для submit указать type; disabled передавать через `:disabled="true"`. Переходы оформлять нативными ссылками приложения |

Blade attribute bag сохраняет пользовательские class, id, data- и ARIA-атрибуты. У Scope data-атрибуты темы/плотности принадлежат компоненту и задаются через props. Недопустимые enum-значения вызывают `InvalidArgumentException`, чтобы ошибочная конфигурация не давала молча другое оформление.

Button использует нативные Enter/Space и disabled. loading добавляет Spinner, aria-busy и блокирует нажатие; icon-only собирается иконкой Lucide в slot и aria-label на кнопке. Фокус обозначается нейтральной рамкой и подчёркиванием без внешнего кольца. Иконки принадлежат оригинальному набору Lucide.

Примеры состояний:

```blade
<x-ui::scope theme="light" density="compact">
    <x-ui::button>Основное действие</x-ui::button>
    <x-ui::button variant="outline">Дополнительное действие</x-ui::button>
    <x-ui::button :disabled="true">Недоступно</x-ui::button>
</x-ui::scope>

<x-ui::scope theme="dark">
    <x-ui::button variant="outline">Длинная подпись дополнительного действия</x-ui::button>
</x-ui::scope>
```

Это фрагменты состояний, не отдельный HTML-renderer каталога. Проверка рендеринга новых атомов выполнена; полная ручная приёмка взаимодействий ещё не завершена. Assets теперь также подключает готовый ES module для Select, mixed-checkbox и fallback Avatar; настройка сборки у потребителя не нужна. Runtime экспортирует enhance(root)/destroy(root), подробнее — [атомарный каталог](atomic-catalog.md).

## Настройки и обновления

Необязательная публикация конфига:

```sh
php artisan vendor:publish --tag=aestetic-ui-config
```

В `config/aestetic-ui.php` приложения можно выбрать `theme` и `density`; props Scope имеют приоритет. Копировать Blade-шаблоны в приложение для обычного подключения не требуется.

Обновление кода и ассетов — одна операция развёртывания:

```sh
composer update aestetic/ui
php artisan vendor:publish --tag=aestetic-ui-assets --force
php artisan view:clear
```

После этого приложение может пересоздать свои обычные deployment-кэши. При откате восстановить предыдущий `composer.lock`, выполнить `composer install`, снова опубликовать соответствующие ассеты с `--force` и очистить скомпилированные Blade views. Не редактировать CSS в `public/vendor`: следующая публикация перезапишет файлы.

CSS имеет имя с хешем содержимого; provider и `<x-ui::assets />` читают manifest установленного пакета. Пакет и public-ассеты нужно выкладывать согласованно: без повторной публикации HTML будет ссылаться на отсутствующий новый файл. `UrlGenerator::asset` учитывает стандартный Laravel `ASSET_URL`; при CDN его файлы доставляет процесс развёртывания приложения.

## Подготовка ассетов в самой библиотеке

Редактируемые источники — `resources/css/aestetic-ui.css` и `resources/js/aestetic-ui.js`. `dist` содержит поставляемые файлы с хешами и manifest/SHA-256. Для подготовки поставки:

```sh
composer build:assets
```

Команда выполнена при подготовке первого alpha-выпуска. Она не запускается автоматически у потребителя. Установку архива и ограничения проверок описывает [отчёт первого выпуска](first-release.md).

## Канал распространения

GitHub + Packagist выбран пользователем (U-24). Публичная установка `aestetic/ui:0.1.0-alpha.1` выполнена во внутреннем workbench без path/VCS-источника. Пакет автоматически обновляется из GitHub. Совместимость с другими Laravel-версиями добавляется явно, а не подразумевается словами «любой проект».

Основания реализации: [Laravel 12: package discovery и публикация ресурсов](https://laravel.com/docs/12.x/packages), [Blade: anonymous component paths](https://laravel.com/docs/12.x/blade#anonymous-component-paths), [Composer: path repositories](https://getcomposer.org/doc/05-repositories.md#path).

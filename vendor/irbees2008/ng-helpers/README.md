## Коллекция вспомогательных функций для плагинов системы NGCMS

### Системные требования

Перед использованием плагина вам необходимо убедиться, что ваш сервер соответствует следующим требованиям:

 - PHP ^7.3|^8.0

### Подключение

Плагин выпускается в двух вариациях, каждая из которых поддерживает свой тип кодировки: UTF-8 и Windows-1251. Для подключения вы можете просто скачать плагин в необходимой для вашего проекта кодировке:

 - [UTF-8](https://codeload.github.com/irbees2008/ng-helpers/zip/master)
 - [Windows-1251](https://codeload.github.com/irbees2008/ng-helpers/zip/windows-1251)

Либо воспользуйтесь менеджером Composer:

```bash
composer require irbees2008/ng-helpers
```

> Обратите внимание, что кодировка UTF-8 является основной.

<!-- ```bash
composer require russsiq/ng-helpers:dev-windows-1251
``` -->

### Настройка

В данный момент плагин не имеет настроек, но активация плагина в панели управления является обязательной.

### Использование

Так как плагин использует пространство имен `Plugins`, то перед использованием отдельно взятой функции, вам необходимо явное указание имени функции, в том случае, если текущее пространство отличается:

```php
// Импортирование необходимых функций (PHP 5.6+)
use function Plugins\functionName;

// Импортирование необходимых функций
// с использованием псевдонима функции (PHP 5.6+)
use function Plugins\functionName as func;
```

<details>
<summary>Доступные методы</summary>

 - [array_dotset](#method-array_dotset)
 - [cache](#method-cache)
 - [cacheRemember](#method-cacheRemember)
 - [catz](#method-catz)
 - [config](#method-config)
 - [database](#method-database)
 - [dd](#method-dd)
 - [pageInfo](#method-pageInfo)
 - [request](#method-request)
 - [setting](#method-setting)
 - [starts_with](#method-starts_with)
 - [trans](#method-trans)
 - [value](#method-value)
 - [view](#method-view)

</details>

<a name="method-array_dotset"></a>
#### `array_dotset`

Установить значение элементу массива, используя «точечную» нотацию.:

```php
use function Plugins\array_dotset;

$array = [
    'keys' => [
        'first' => 450,
        'second' => 460
    ],

];

array_dotset($array, 'keys.second', 800);

// [
//     'keys' => [
//         'first' => 450,
//         'second' => 800
//     ],
//
// ]
```

<a name="method-cache"></a>
#### `cache`

Получить данные из кэша, либо сохранить указанные данные в кэш.

```php
use function Plugins\cache;

// Сохранить данные в кеш.
cache($plugin, md5('key'), 'value');
```

```php
use function Plugins\cache;

// Получить данные из кеша.
$value = cache($plugin, md5('key'));
```

> Обратите внимание, что указываемый плагин `$plugin` должен иметь настройку `cache`, значение которой должно быть установлено как `true`.

<a name="method-cacheRemember"></a>
#### `cacheRemember`

Получить данные из кэша, либо выполнить замыкание и сохранить результат в кэш.

```php
use function Plugins\cacheRemember;

$rows = cacheRemember($plugin, md5('key'), function () {
    return database()
        ->select(
            "select * from `".prefix."_news` where `approve` = 1 order by `views` desc limit 20"
        );
});
```

> Обратите внимание, что указываемый плагин `$plugin` должен иметь настройку `cache`, значение которой должно быть установлено как `true`.

<a name="method-catz"></a>
#### `catz`

Получить категорию по идентификатору, либо массив всех категорий.

```php
use function Plugins\catz;

$categories = catz();

// Итерация всех категорий.
foreach ($categories as $id => $data) {
    //
}
```

```php
use function Plugins\catz;

// Получить категорию с идентификатором `28`.
$category = catz(28);
```

<a name="method-config"></a>
#### `config`

Получить значение конфигурации системы.

```php
use function Plugins\config;

$value = config('key', 'default');
```

<a name="method-database"></a>
#### `database`

Получить текущее подключение к базе данных.

```php
use function Plugins\database;

$rows = database()->select(
    "select * from `".prefix."_news` where `approve` = 1 order by `views` desc limit 20"
);
```

> Обратите внимание, что при использовании данной функции вы несёте полную ответственность [за выполняемые запросы к БД](https://ru.wikipedia.org/wiki/Внедрение_SQL-кода).

<a name="method-dd"></a>
#### `dd`

Распечатать переменную, массив переменных или объект и прекратить выполнение скрипта.

```php
use function Plugins\dd;

$array = [
    'keys' => [
        'first' => 450,
        'second' => 460
    ],

];

dd($array);

// Array
// (
//     [keys] => Array
//         (
//             [first] => 450
//             [second] => 460
//         )
//
// )
```

> Обратите внимание, что использование данной функции допустимо только в режиме отладки приложения.

<a name="method-pageInfo"></a>
#### `pageInfo`

Установить системную информацию о текущей странице.

```php
use function Plugins\pageInfo;

pageInfo('key', 'value');
```

```php
// Примеры использования.

$breadcrumbs[] = [
    'link' => $this->pluginLink,
    'text' => trans('x_filter:title'),

];

pageInfo('info.breadcrumbs', $breadcrumbs);

pageInfo('meta.description', $description);
pageInfo('meta.keywords', $keywords);
```

<a name="method-request"></a>
#### `request`

Получить значение из глобального `$_REQUEST`.

```php
use function Plugins\request;

$value = request('key', 'default');
```

```php
// Пример использования.
$currentPage = (int) request('page', 1);
```

<a name="method-setting"></a>
#### `setting`

Получить настройку плагина по ключу, либо задать массив настроек.

```php
use function Plugins\setting;

// Получить настройку плагина.
$value = setting($plugin, 'key', 'default');

// Задать массив настроек плагина.
setting($plugin, [
    'key' => 'value',
    'another_key' => 'another_value'
]);
```

Примеры использования:

```php
// Получить настройку плагина.
$cacheExpire = (int) setting($plugin, 'cacheExpire', 60);
```

```php
// Задать массив настроек плагина.
setting($plugin, [
    // Использовать кеширование данных.
    'cache' => 0,

    // Период обновления кеша.
    'cacheExpire' => 60,

]);
```

<a name="method-starts_with"></a>
#### `starts_with`

Определить, начинается ли переданная строка с указанной подстроки.

```php
use function Plugins\starts_with;

$result = starts_with('Строка для примера', 'Строка ');

// true
```

<a name="method-trans"></a>
#### `trans`

Получить перевод строки.

```php
use function Plugins\trans;

$string = trans('key');
```

```php
// Пример использования.
$charset = trans('encoding');
```

<a name="method-value"></a>
#### `value`

Возвращает значение по умолчанию для переданного значения.

```php
use function Plugins\value;

$result = value(true);

// true

$result = value(function () {
    return false;
});

// false
```

<a name="method-view"></a>
#### `view`

Выводит шаблон с заданным контекстом и возвращает его в виде строки.

```php
use function Plugins\view;

$context = [
    'key' => 'value'
];

return view($template, $context);
```

### Лицензия

`ng-helpers` - программное обеспечение с открытым исходным кодом, распространяющееся по лицензии [MIT](LICENSE).

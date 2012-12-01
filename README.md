PHP CLASS MAP [en]
==================
Script for generating autoloader based on the map of classes in your project.
Support PHP 5.3 (namespace required).

USAGE
-------------
Just download [map.phar](https://github.com/downloads/dmkuznetsov/php-class-map/map.phar) (from downloads)
and run next command in terminal:

`php map.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/files --verbose`

Script will create file "/path/to/file_for_autoload.php" with autoloader. Just include this in your project:

```php
<?php
include '/path/to/file_for_autoload.php';
```

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


PHP CLASS MAP [ru]
==================
Скрипт для генерации автозагрузчика на основании карты классов вашего проекта.
Поддерживает версию PHP 5.3 (включая namespace).

КАК ПОЛЬЗОВАТЬСЯ
----------------
Скачайте [map.phar](https://github.com/downloads/dmkuznetsov/php-class-map/map.phar) (из раздела downloads)
и выполните команду в консоли:

`php map.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/files --verbose`

Скрипт создаст файл "/path/to/file_for_autoload.php" (если это возможно) с автозагрузчиком. Просто подключите его в вашем проекте:

```php
<?php
include '/path/to/file_for_autoload.php';
```


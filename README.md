PHP CLASS MAP AUTOLOADER [en]
=============================
Script for generating autoloader based on the map of classes in your project.
Support PHP 5.3 (namespace required).

USAGE
-------------
Just download [map.phar](http://dmkuznetsov.com/shared/map.phar)
and run next command in terminal:

`php map.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/files`

Script will create file "/path/to/file_for_autoload.php" with autoloader. Just include this in your project:

```php
<?php
include '/path/to/file_for_autoload.php';
```

By default, script will generate absolute paths. If you need relative paths - use next command:

`php map.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/file --relative-path`



PHP CLASS MAP AUTOLOADER [ru]
=============================
Скрипт для генерации автозагрузчика на основании карты классов вашего проекта.
Поддерживает версию PHP 5.3 (включая namespace).

КАК ПОЛЬЗОВАТЬСЯ
----------------
Скачайте [map.phar](http://dmkuznetsov.com/shared/map.phar)
и выполните команду в консоли:

`php map.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/file`

Скрипт создаст файл "/path/to/file_for_autoload.php" (если это возможно) с автозагрузчиком. Просто подключите его в вашем проекте:

```php
<?php
include '/path/to/file_for_autoload.php';
```

По-умолчанию, скрипт записывает абсолютные пути. Если вам нужно, чтобы были сгенерированы относительные пути - используйте команду:

`php map.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/file --relative-path`


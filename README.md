PHP CLASS MAP [en]
==================
Script for generation map of php files.
Support PHP 5.3 (namespace required).

USAGE
-------------
php map.php --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/files --verbose

OR you can use "map.phar" from downloads.


Autoload
---------
After generation put in your index.php

```php
<?php
include '/path/to/file_for_autoload.php';
```


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


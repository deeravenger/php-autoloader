PHP CLASS MAP
=============
Script for generation map of php files.

Support PHP 5.3 (namespace required).


How to use
----------
php map.php --file=/path/to/class_map.php --dir=/path/to/dir/where/php/files --verbose --help


Autoload
---------
For simple autoload edit file "autoload.php".
After that put in your index.php

```php
<?php
include 'autoload.php';
```
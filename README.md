PHP CLASS MAP
=============
Script for generation map of php files.
Support PHP 5.3 (namespace required).

USAGE
-------------
php map.php --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/files --verbose --help


Autoload
---------
After generation put in your index.php

```php
<?php
include '/path/to/file_for_autoload.php';
```
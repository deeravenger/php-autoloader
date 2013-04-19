<?php

$root = dirname(__FILE__);
$dir = $root . '/../src';
$pharFile = $root . '/autoload.phar';
@unlink($pharFile);

$p = new Phar($pharFile, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME);
$stub = <<<STUB
#!/usr/bin/env php
<?php
Phar::mapPhar();
chdir(__DIR__);

require_once 'phar://autoload.phar/index.php';
require_once 'phar://autoload.phar/src/Autoload/LogInterface.php';
require_once 'phar://autoload.phar/src/Autoload/Log.php';
require_once 'phar://autoload.phar/src/Autoload/Info.php';
require_once 'phar://autoload.phar/src/Autoload.php';
__HALT_COMPILER();
?>
STUB;
$p->setStub($stub);

$p->startBuffering();
$p->addFile($root . '/../autoload.php', 'autoload.php');
$p->addFile($root . '/../index.php', 'index.php');
$p->addFile($dir . '/Autoload/LogInterface.php', 'src/Autoload/LogInterface.php');
$p->addFile($dir . '/Autoload/Log.php', 'src/Autoload/Log.php');
$p->addFile($dir . '/Autoload/Info.php', 'src/Autoload/Info.php');
$p->addFile($dir . '/Autoload.php', 'src/Autoload.php');
$p->stopBuffering();

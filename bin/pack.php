<?php

$root = dirname( __FILE__ );
$dir = $root . '/../src';
$pharFile = $root . '/map.phar';
@unlink( $pharFile );

$p = new Phar($pharFile, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME);
$p->startBuffering();
$p->addFile( $root . '/../autoload.php', 'autoload.php' );
$p->addFile( $root . '/../map.php', 'index.php' );
$p->addFile( $dir . '/Autoload/LogInterface.php', 'src/Autoload/LogInterface.php' );
$p->addFile( $dir . '/Autoload/Log.php', 'src/Autoload/Log.php' );
$p->addFile( $dir . '/Autoload/Info.php', 'src/Autoload/Info.php' );
$p->addFile( $dir . '/Autoload.php', 'src/Autoload.php' );

//$p->buildFromIterator(iter($dir),$dir);

$p->stopBuffering();



function iter( $dir )
{
	return new RecursiveIteratorIterator (new RecursiveDirectoryIterator($dir) );
}
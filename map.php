<?php
/**
 * Universal php auto loader
 *
 * @link      http://github.com/dmkuznetsov/php-autoloader
 * @copyright Copyright (c) 2012-2013 Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license   http://raw.github.com/dmkuznetsov/php-autoloader/master/LICENSE.txt New BSD License
 */
require_once dirname( __FILE__ ) . '/classes/LogInterface.php';
require_once dirname( __FILE__ ) . '/classes/Log.php';
require_once dirname( __FILE__ ) . '/classes/Info.php';
require_once dirname( __FILE__ ) . '/classes/Main.php';

$options = getopt( '', array( 'dir:', 'file:', 'suffix:', 'absolute-path', 'no-verbose', 'help' ) );
if ( array_key_exists( 'help', $options ) )
{
	help();
}
checkOptions( $options );

$verbose = !array_key_exists( 'no-verbose', $options );
$relative = !array_key_exists( 'absolute-path', $options );
$options[ 'suffix' ] = isset( $options[ 'suffix' ] ) && !empty( $options[ 'suffix' ] ) ? $options[ 'suffix' ] : rand( 100, 999 );

$log = new \Dm\Utils\Autoload\Log( $verbose );
$log->log( "Start ClassMap generator" );

$info = new \Dm\Utils\Autoload\Info( $log );
$status = $info->checkFileStatus( $options[ 'file' ] );
if ( $status )
{
	$status = $info->checkDirStatus( $options[ 'dir' ] );
}
if ( !$status )
{
	exit( "\nCanceled.\n" );
}

$classMap = new \Dm\Utils\Autoload\Main( $options[ 'file' ], $options[ 'dir' ], $options[ 'suffix' ], $relative, $log );
$classMap->run();
$classMap->save();

exit( "\n" );

/**
 * @param array $options
 */
function checkOptions( array $options )
{
	$messages = array();
	if ( !array_key_exists( 'file', $options ) )
	{
		$messages[ ] = 'Please specify file for input data.' . "\n";
	}
	if ( !array_key_exists( 'dir', $options ) )
	{
		$messages[ ] = 'Please specify dir for analyze.' . "\n";
	}
	if ( !empty( $messages ) )
	{
		array_unshift( $messages, 'ERROR!' );
		showMessage( $messages, false );
		help();
	}
}

function help()
{
	$content = array();
	$content[] = 'PHP CLASS MAP';
	$content[] = 'Script for generation map of php files. Support PHP 5.3 (namespace required).';
	$content[] = 'USAGE';
	$content[] = 'If you use phar file write "php map.phar"';
	$content[] = 'If you use php file write "php map.php"';
	$content[] = 'AVAILABLE OPTIONS';
	$content[] = '--file="path/to/your/autoload.php"';
	$content[] = '--dir="path/to/your/php/classes"';
	$content[] = '--suffix="suffix of autoload function"';
	$content[] = '--absolute-path';
	$content[] = '--no-verbose';
	$content[] = '--help';
	$content[] = '';
	$content[] = 'Dmitry Kuznetsov <kuznetsov2d@gmail.com>, 2012';
	$content[] = 'https://github.com/dmkuznetsov/php-class-map';
	showMessage( $content );
}

function showMessage( $message, $stop = true )
{
	if ( is_array( $message ) )
	{
		$message = implode( "\n", $message );
	}
	echo $message . "\n";
	if ( $stop )
	{
		exit;
	}
}
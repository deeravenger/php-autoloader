<?php
/**
 * Autoload generator
 *
 * @link      http://github.com/dmkuznetsov/php-autoloader
 * @copyright Copyright (c) 2012-2013 Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license   http://raw.github.com/dmkuznetsov/php-autoloader/master/LICENSE.txt New BSD License
 */
require_once dirname(__FILE__) . '/src/Autoload/LogInterface.php';
require_once dirname(__FILE__) . '/src/Autoload/Log.php';
require_once dirname(__FILE__) . '/src/Autoload/Info.php';
require_once dirname( __FILE__ ) . '/src/Autoload.php';

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
$log->log( "Start autoload generator" );

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

if ($relative) {
    $log->log("Use relative paths");
} else {
    $log->log("Use absolute paths");
}

$classMap = new \Dm\Utils\Autoload( $options[ 'file' ], $options[ 'dir' ], $options[ 'suffix' ], $relative, $log );
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
	$content[] = 'PHP AUTOLOAD GENERATOR';
    $content[] = 'Script for generate universal autoload.';
    $content[] = 'Support classes, interfaces, namespaces, traits (PHP >= 5.3 required).';
    $content[] = '';
	$content[] = 'USAGE';
    $content[] = 'php autoload.phar --file=/www/project/autoload.php --dir=/www/project/src --suffix="project_name"';
    $content[] = '';
    $content[] = 'AVAILABLE OPTIONS';
	$content[] = '--file             - path to your autoload.php';
	$content[] = '--dir              - path to your project';
	$content[] = '--suffix           - suffix for autoload function';
	$content[] = '--absolute-path    - use absolute paths';
	$content[] = '--no-verbose       - hide log';
	$content[] = '--help             - show help';
    $content[] = '';
   	$content[] = 'Dmitry Kuznetsov <kuznetsov2d@gmail.com>, 2012-'.date('Y');
   	$content[] = 'https://github.com/dmkuznetsov/php-autoloader';
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
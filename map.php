<?php
/**
 * ClassMap
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright (c) 2012, Dmitry Kuznetsov <kuznetsov2d@gmail.com>. All rights reserved.
 * @author Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @url https://github.com/dmkuznetsov/php-class-map
*/
require_once dirname( __FILE__ ) . '/classes/ClassMap.php';
require_once dirname( __FILE__ ) . '/classes/Writer.php';
require_once dirname( __FILE__ ) . '/classes/Log.php';
require_once dirname( __FILE__ ) . '/classes/Log/None.php';
require_once dirname( __FILE__ ) . '/classes/Log/Console.php';
require_once dirname( __FILE__ ) . '/classes/Progress.php';
require_once dirname( __FILE__ ) . '/classes/Progress/None.php';
require_once dirname( __FILE__ ) . '/classes/Progress/Console.php';

$options = getopt( '', array( 'dir:', 'file:', 'no-verbose', 'help' ) );
if ( array_key_exists( 'help', $options ) )
{
	help();
}
checkOptions( $options );

$verbose = true;
if ( array_key_exists( 'no-verbose', $options ) )
{
	$verbose = false;
}

$log = new ClassMap_Log_Console( $verbose );
$progress = null;
if ( $verbose )
{
	$progress = new ClassMap_Progress_Console();
}

$classMap = new ClassMap( $options[ 'dir' ], $log, $progress );
$classMap->run();

$writer = new ClassMap_Writer( $classMap->getMap(), $log );
$writer->save( $options[ 'file' ] );

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
	$content[] = '--file="path/to/your/autoloader.php"';
	$content[] = '--dir="path/to/your/php/classes"';
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
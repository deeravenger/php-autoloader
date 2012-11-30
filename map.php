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
 * @copyright (c) 2012, Dmitry Kuznetsov <dev.kuznetsov@gmail.com>. All rights reserved.
 * @author Dmitry Kuznetsov <dev.kuznetsov@gmail.com>
 * @url https://github.com/dmkuznetsov/php-class-map
*/
require_once dirname( __FILE__ ) . '/classes/ClassMap.php';
require_once dirname( __FILE__ ) . '/classes/Writer.php';
require_once dirname( __FILE__ ) . '/classes/Log.php';
require_once dirname( __FILE__ ) . '/classes/Progress.php';

$options = getopt( '', array( 'dir:', 'file:', 'verbose', 'help' ) );
if ( array_key_exists( 'help', $options ) )
{
	help();
}
checkOptions( $options );

$verbose = false;
if ( array_key_exists( 'verbose', $options ) )
{
	$verbose = true;
}

$log = ClassMap_Log::get( 'Console' );
$log->setVerbose( $verbose );

$classMap = new ClassMap( $options[ 'dir' ], $log, ClassMap_Progress::get( 'Console' ) );
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
	$content = file_get_contents( dirname( __FILE__ ) . '/README.md' );
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
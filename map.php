<?php
/**
 * Script for generation array-map of php-classes for autoload
 *
 * Use:
 * php map.php --file=/path/to/class_map.php --dir=/path/to/dir/where/php/files
 *
 * @author Dmitry Kuznetsov 2012
 * @url https://github.com/dmkuznetsov/php-class-map
 */

$options = getopt( '', array( 'dir:', 'file:', 'help' ) );

if ( array_key_exists( 'help', $options ) )
{
	help();
}
checkOptions( $options );

$map = getClassMap( $options[ 'dir' ] );
if ( empty( $map ) )
{
	showMessage( sprintf( 'PHP classes not found in path %s', $options[ 'dir' ] ) );
}
$status = saveMap( $options[ 'file' ], $map );

$messages = array();
if ( !$status )
{
	$messages[ ] = 'ERROR!';
	$messages[ ] = 'Can\'t save to file ' . $options[ 'file' ];
}
else
{
	$messages[ ] = 'SUCCESS!';
	$messages[ ] = 'In directory ' . $options[ 'dir' ] . ' was found ' . count( $map ) . ' classes.';
	$messages[ ] = 'This successfully saved to file ' . $options[ 'file' ];
}
showMessage( $messages );


/**
 * @param $fileName
 * @param array $map
 * @return bool
 */
function saveMap( $fileName, array $map )
{
	$content = '<?php return ' . var_export( $map, true ) . ';';
	return file_put_contents( $fileName, $content ) ? true : false;
}

/**
 * @param $dir
 * @return array
 */
function getClassMap( $dir )
{
	$dir = rtrim( $dir, '/' );
	$result = array();
	$fileList = fileList( $dir . '/*.php' );
	foreach ( $fileList as $fileName )
	{
		$list = getClassesFromFile( $fileName );
		foreach ( $list as $className )
		{
			$result[ $className ] = $fileName;
		}
	}
	ksort( $result );

	return $result;
}

/**
 * @param $pattern
 * @param int $flags
 * @return array
 */
function fileList( $pattern, $flags = 0 )
{
	$files = glob( $pattern, $flags );
	foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir )
	{
		$files = array_merge( $files, fileList( $dir . '/' . basename( $pattern ), $flags ) );
	}
	return $files;
}

/**
 * @param $fileName
 * @return array
 */
function getClassesFromFile( $fileName )
{
	$result = array();
	$content = file_get_contents( $fileName );
	$tokens = token_get_all( $content );
	$waitingClassName = false;
	for ( $i = 0, $c = count( $tokens ); $i < $c; $i++ )
	{
		if ( is_array( $tokens[ $i ] ) )
		{
			list( $type, $value ) = $tokens[ $i ];
			switch ( $type )
			{
				case T_CLASS:
				case T_INTERFACE:
					$waitingClassName = true;
					break;
				case T_STRING:
					if ( $waitingClassName )
					{
						$result[ ] = $value;
						$waitingClassName = false;
					}
					break;
			}
		}
	}
	return $result;
}

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
	$messages = array();
	$messages[ ] = 'HELP';
	$messages[ ] = '';
	$messages[ ] = 'Example:';
	$messages[ ] = 'php map.php --file=/www/project/class_map.php --dir=/www/project/';
	$messages[ ] = '';
	$messages[ ] = 'Script will create file class_map.php (if it possible) with array of all classes in dir /www/project';
	$messages[ ] = '';
	$messages[ ] = 'Good luck!';

	showMessage( $messages );
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

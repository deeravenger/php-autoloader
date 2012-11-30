<?php
/**
 * @author Dmitry Kuznetsov 2012
 * @url https://github.com/dmkuznetsov/php-class-map
 */
abstract class ClassMap_Progress
{
	abstract public function start( $count = null );

	abstract public function update( $number = 0 );

	abstract public function stop();

	/**
	 * @param string $name
	 * @throws Exception
	 * @return self
	 */
	public static function get( $name = 'None' )
	{
		$files = glob( dirname( __FILE__ ) . '/Progress/*.php' );
		foreach ( $files as $filePath )
		{
			$fileName = pathinfo( $filePath, PATHINFO_FILENAME );
			if ( strcasecmp( $name, $fileName ) == 0 )
			{
				$className = sprintf( 'ClassMap_Progress_%s', $name );
				require $filePath;
				return new $className();
			}
		}
		throw new Exception( 'Not found ClassMap_Log_' . $name );
	}
}
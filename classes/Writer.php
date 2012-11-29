<?php
/**
 * @author Dmitry Kuznetsov 2012
 * @url https://github.com/dmkuznetsov/php-class-map
 */
class Writer
{
	/**
	 * @var array
	 */
	protected $_map;
	/**
	 * @var Log
	 */
	protected $_log;

	public function __construct( array $map, Log $log )
	{
		$this->_map = $map;
		$this->_log = $log;
	}

	public function save( $file )
	{
		$this->_checkFile( $file );
		$this->_createFile( $file );

		$this->_log->log( "Start writing class map to file..." );
		$success = $this->_writeToFile( $file );
		if ( $success )
		{
			$this->_log->log( "Success! Please, check file %s", $file );
		}
		else
		{
			$this->_log->log( "Error! Can't write to file %s", $file );
		}
	}

	protected function _checkFile( $file )
	{
		$this->_log->log( 'FILE: %s', $this->_getFileStatus( $file ) );
	}

	protected function _createFile( $file )
	{
	}

	protected function _writeToFile( $file )
	{
		$content = file_get_contents( dirname( __FILE__ ) . '/../autoload.php' );
		$content = str_replace( 'array()', var_export( $this->_map, true ), $content );
		$bytes = file_put_contents( $file, $content );
		return $bytes ? true : false;
	}

	private function _getFileStatus( $file )
	{
		if ( file_exists( $file ) )
		{
			if ( is_file( $file ) )
			{
				$status = 'found';
				if ( is_writable( $file ) )
				{
					$status .= ', writable';
				}
			}
			else
			{
				$status = 'is dir, not file';
			}
		}
		else
		{
			$status = 'not found';
			$dir = dirname( $file );
			if ( !is_dir( $dir ) )
			{
				$status .= ', parent dir not found';
			}
			else
			{
				if ( is_writable( $dir ) )
				{
					$status .= ', parent dir writable';
				}
				else
				{
					$status .= ', parent dir writable';
				}
			}
		}
		return $file . ' (' . $status . ')';
	}
}
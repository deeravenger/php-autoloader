<?php
/**
 * Universal php auto loader
 *
 * @link      http://github.com/dmkuznetsov/php-autoloader
 * @copyright Copyright (c) 2012-2013 Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license   http://raw.github.com/dmkuznetsov/php-autoloader/master/LICENSE.txt New BSD License
 */
namespace UniversalAutoloader;

class Info
{
	/**
	 * @var \UniversalAutoloader\Log
	 */
	protected $_log;

	public function __construct( Log $log )
	{
		$this->_log = $log;
	}

	/**
	 * @param $path
	 * @return bool
	 */
	public function checkFileStatus( $path )
	{
		list( $status, $msg ) = $this->_getFileStatus( $path );
		$this->_log->log( 'FILE: %s', $msg );

		return $status;
	}

	/**
	 * @param $path
	 * @return bool
	 */
	public function checkDirStatus( $path )
	{
		list( $status, $msg ) = $this->_getDirWithStatus( $path );
		$this->_log->log( 'DIR: %s', $msg );

		return $status;
	}

	/**
	 * @param $dir
	 * @return array
	 */
	private function _getDirWithStatus( $dir )
	{
		$result = false;
		$status = 'not found';
		if ( is_dir( $dir ) )
		{
			$status = 'found';
			if ( is_readable( $dir ) )
			{
				$status .= ', readable';
				$result = true;
			}
		}
		return array( $result, $dir . ' (' . $status . ')' );
	}

	/**
	 * @param $file
	 * @return array
	 */
	private function _getFileStatus( $file )
	{
		$result = false;
		if ( file_exists( $file ) )
		{
			if ( is_file( $file ) )
			{
				$status = 'found';
				if ( is_writable( $file ) )
				{
					$status .= ', writable';
					$result = true;
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
					$result = true;
				}
				else
				{
					$status .= ', parent dir NOT writable';
				}
			}
		}
		return array( $result, $file . ' (' . $status . ')' );
	}
}
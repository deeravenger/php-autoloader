<?php
include 'Log.php';
include 'Progress.php';

if ( !defined( 'T_ML_COMMENT' ) )
{
	define( 'T_ML_COMMENT', T_COMMENT );
}
else
{
	define( 'T_DOC_COMMENT', T_ML_COMMENT );
}

/**
 * @author Dmitry Kuznetsov 2012
 * @url https://github.com/dmkuznetsov/php-class-map
 */
class ClassMap
{
	/**
	 * @var string
	 */
	protected $_file;
	/**
	 * @var string
	 */
	protected $_dir;
	/**
	 * @var Log
	 */
	protected $_log;
	/**
	 * @var Progress
	 */
	protected $_progress;

	/**
	 * @var array
	 */
	private $_messages = array();
	/**
	 * @var array
	 */
	private $_files = array();
	/**
	 * @var int
	 */
	private $_filesCount = 0;
	/**
	 * @var array
	 */
	private $_classMap = array();
	/**
	 * @var int
	 */
	private $_classMapCount = 0;

	/**
	 * @param string $file
	 * @param string $dir
	 * @param Log $log
	 * @param Progress $progress
	 */
	public function __construct( $file, $dir, Log $log = null, Progress $progress = null )
	{
		$this->_file = $file;
		$this->_dir = rtrim( $dir, '/' );
		$this->_log = $log;
		$this->_progress = $progress;
	}

	public function run()
	{
		$this->_log( "ClassMap: init with params:\nFILE: %s\nDIR: %s", $this->_getFileWithStatus(), $this->_getDirWithStatus() );

		$this->_log( "Start searching php files..." );
		$this->_searchFiles();
		$this->_log( "Found %d php-files", $this->_filesCount );

		$this->_log( "Start analyzing files for classes..." );
		$this->_buildClassMap();
		$this->_log( "Found %d classes", $this->_classMapCount );

		$this->_log( "Start writing class map to file..." );
		$success = $this->_save();
		if ( $success )
		{
			$this->_log( "Success! Please, check file %s", $this->_file );
		}
		else
		{
			$this->_log( "Error! Can't write to file %s", $this->_file );
		}
	}

	/**
	 * @return array
	 */
	public function getLog()
	{
		return $this->_messages;
	}

	protected function _searchFiles()
	{
		$this->_progress( 'start' );
		$this->_files = $this->_getFileList( $this->_dir . '/*.php' );
		$this->_progress( 'stop' );

		$this->_filesCount = count( $this->_files );
	}

	protected function _buildClassMap()
	{
		$this->_progress( 'start', $this->_filesCount );
		for ( $i = 0; $i < $this->_filesCount; $i++ )
		{
			$list = $this->_getClasses( $this->_files[ $i ] );
			foreach ( $list as $className )
			{
				$this->_classMap[ $className ] = $this->_files[ $i ];
			}
			$this->_classMapCount += count( $list );
			$this->_progress( 'update', $i );
		}
		$this->_progress( 'stop' );
//		ksort( $this->_classMap );
	}

	/**
	 * @return int
	 */
	protected function _save()
	{
		$content = '<?php return ' . var_export( $this->_classMap, true ) . ';';
		return file_put_contents( $this->_file, $content );
	}

	/**
	 * @param $pattern
	 * @param int $flags
	 * @return array
	 */
	protected function _getFileList( $pattern, $flags = 0 )
	{
		$files = glob( $pattern, $flags );
		foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir )
		{
			$files = array_merge( $files, $this->_getFileList( $dir . '/' . basename( $pattern ), $flags ) );
			$this->_progress( 'update' );
		}
		return $files;
	}

	/**
	 * @param $fileName
	 * @return array
	 */
	protected function _getClasses( $fileName )
	{
		$result = array();
		$content = file_get_contents( $fileName );
		$tokens = token_get_all( $content );
		$waitingClassName = false;
		$waitingNamespace = false;
		$namespace = '';
		for ( $i = 0, $c = count( $tokens ); $i < $c; $i++ )
		{
			if ( is_array( $tokens[ $i ] ) )
			{
				list( $id, $value ) = $tokens[ $i ];
				switch ( $id )
				{
					case T_NAMESPACE:
						$waitingNamespace = true;
						$namespace = '';
						break;
					case T_CLASS:
					case T_INTERFACE:
						$waitingClassName = true;
						break;
					case T_STRING:
						if ( $waitingNamespace )
						{
							$namespace = $value;
							$waitingNamespace = false;
						}
						elseif ( $waitingClassName )
						{
							if ( !empty( $namespace ) )
							{
								$value = sprintf( '%s/%s', $namespace, $value );
							}
							$result[ ] = $value;
							$waitingClassName = false;
						}
						break;
				}
			}
			else
			{
				if ( $waitingNamespace && $tokens[ $i ] == '{' )
				{
					$waitingNamespace = false;
				}
			}
		}
		return $result;
	}

	protected function _log()
	{
		if ( !is_null( $this->_log ) )
		{
			call_user_func_array( array( $this->_log, 'log' ), func_get_args() );
		}
	}

	protected function _progress()
	{
		$args = func_get_args();
		$method = array_shift( $args );
		if ( !is_null( $this->_progress ) )
		{
			call_user_func_array( array( $this->_progress, $method ), $args );
		}
	}

	private function _getFileWithStatus()
	{
		if ( file_exists( $this->_file ) )
		{
			if ( is_file( $this->_file ) )
			{
				$status = 'found';
				if ( is_writable( $this->_file ) )
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
			$dir = dirname( $this->_file );
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
		return $this->_file . ' (' . $status . ')';
	}

	private function _getDirWithStatus()
	{
		$status = 'not found';
		if ( is_dir( $this->_dir ) )
		{
			$status = 'found';
			if ( is_readable( $this->_dir ) )
			{
				$status .= ', readable';
			}
		}
		return $this->_dir . ' (' . $status . ')';
	}
}

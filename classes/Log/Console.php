<?php
require dirname( __FILE__ ) . '/../Log.php';
/**
 * @author Dmitry Kuznetsov 2012
 * @url https://github.com/dmkuznetsov/php-class-map
 */
class ClassMap_Log_Console extends ClassMap_Log
{
	protected $_verboseMode;
	private $_data = array();

	public function __construct( $verbose = true )
	{
		$this->_verboseMode = $verbose;
	}

	public function log()
	{
		$date = date( 'H:i:s' );
		$message = $date . ' ' . call_user_func_array( 'sprintf', func_get_args() );
		$this->_data[] = $message;
		if ( $this->_verboseMode )
		{
			echo "\n" . $message;
		}
	}

	public function getLog()
	{
		return $this->_data;
	}
}
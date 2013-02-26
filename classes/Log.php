<?php
/**
 * ClassMap
 *
 * @link      http://github.com/dmkuznetsov/php-autoloader
 * @copyright Copyright (c) 2012-2013 Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license   http://raw.github.com/dmkuznetsov/php-autoloader/master/LICENSE.txt New BSD License
 */
namespace ClassMap;

class Log implements LogInterface
{
	/**
	 * @var bool
	 */
	protected $_verboseMode;
	private $_data = array();

	private $_status = false;
	private $_count;
	private $_state = 0;
	const MIN_FOR_RULE = 25;

	public function __construct( $verbose = true )
	{
		$this->_verboseMode = $verbose;
	}

	/**
	 * (PHP 4, PHP 5)<br/>
	 * Return a formatted string
	 * @link http://php.net/manual/en/function.sprintf.php
	 * @param string $format <p>
	 * The format string is composed of zero or more directives:
	 * ordinary characters (excluding %) that are
	 * copied directly to the result, and conversion
	 * specifications, each of which results in fetching its
	 * own parameter. This applies to both sprintf
	 * and printf.
	 * </p>
	 * <p>
	 * Each conversion specification consists of a percent sign
	 * (%), followed by one or more of these
	 * elements, in order:
	 * An optional sign specifier that forces a sign
	 * (- or +) to be used on a number. By default, only the - sign is used
	 * on a number if it's negative. This specifier forces positive numbers
	 * to have the + sign attached as well, and was added in PHP 4.3.0.
	 * @param mixed $args [optional] <p>
	 * </p>
	 * @param mixed $_ [optional]
	 * @return string a string produced according to the formatting string
	 * format.
	 */
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

	public function startProgress( $count = null )
	{
		if ( !is_null( $count ) && $count >= self::MIN_FOR_RULE )
		{
			$this->_showRule();
		}
		$this->_status = true;
		$this->_count = $count;
		$this->_state = 0;
	}

	public function updateProgress( $number = 0 )
	{
		if ( is_null( $this->_count ) )
		{
			$this->_updateUnlimited();
		}
		else
		{
			$this->_updateLimited( $number );
		}
	}

	public function stopProgress()
	{
		$this->_status = false;
		$this->_state = 0;
	}

	private function _updateUnlimited()
	{
		list( $micro, ) = explode( ' ', microtime() );
		$micro *= 100;
		if ( !$this->_state || $micro > $this->_state + 5 )
		{
			$this->_state = $micro;
			echo ".";
		}
	}

	private function _updateLimited( $number )
	{
		if ( $this->_count >= self::MIN_FOR_RULE )
		{
			$percent = (int) ($number * 100 / $this->_count );
			if ( $percent % 2 && $percent >= $this->_state + 2 )
			{
				$this->_state = $percent;
				echo "=";
			}
		}
	}

	private function _showRule()
	{
		$length = 50;
		$hint = "100%";
		$content = "\n[";
		for ( $i = 0, $c = $length - strlen( $hint ); $i < $c; $i++ )
		{
			if ( $i == $c / 2 - strlen( $hint ) / 2 )
			{
				$content .= $hint;
			}
			else
			{
				$content .= "-";
			}
		}
		$content .= "]\n ";
		echo $content;
	}
}
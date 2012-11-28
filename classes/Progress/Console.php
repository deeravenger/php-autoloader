<?php

class Progress_Console extends Progress
{
	private $_status = false;
	private $_count;
	private $_state = 0;
	const MIN_FOR_RULE = 25;

	public function start( $count = null )
	{
		if ( !is_null( $count ) && $count >= self::MIN_FOR_RULE )
		{
			$this->_showRule();
		}
		$this->_status = true;
		$this->_count = $count;
		$this->_state = 0;
	}

	public function update( $number = 0 )
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

	public function stop()
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
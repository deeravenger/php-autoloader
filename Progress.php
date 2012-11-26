<?php

abstract class Progress
{

	abstract public function start( $count = null );
	abstract public function update( $number = 0 );
	abstract public function stop();
}
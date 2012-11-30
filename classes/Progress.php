<?php
/**
 * @author Dmitry Kuznetsov 2012
 * @url https://github.com/dmkuznetsov/php-class-map
 */
class ClassMap_Progress
{
	const START = 'start';
	const UPDATE = 'update';
	const STOP = 'stop';

	public function start( $count = null )
	{}

	public function update( $number = 0 )
	{}

	public function stop()
	{}
}
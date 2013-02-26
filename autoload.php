<?php
/**
 * ClassMap
 *
 * @link      http://github.com/dmkuznetsov/php-autoloader
 * @copyright Copyright (c) 2012-2013 Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license   http://raw.github.com/dmkuznetsov/php-autoloader/master/LICENSE.txt New BSD License
 */
function __autoload_by_map( $className )
{
	$map = array();
	$className = str_replace( '\\', '/', $className );
	if ( !class_exists( $className ) && isset( $map[ $className ] ) )
	{
		include $map[ $className ];
	}
}
spl_autoload_register( '__autoload_by_map' );

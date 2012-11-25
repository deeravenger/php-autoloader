<?php

function autoloader( $className )
{
	static $map = array();
	if ( empty( $map ) )
	{
		$map = include 'map.php';
	}
	if ( !class_exists( $className ) && isset( $map[ $className ] ) )
	{
		include $map[ $className ];
	}
}
spl_autoload_register( 'autoloader' );

<?php

$map = include 'path/to/your/map.php';

function load_class_by_map( $className )
{
	global $map;
	if ( !class_exists( $className ) && isset( $map[ $className ] ) )
	{
		include $map[ $className ];
	}
}

spl_autoload_register( 'load_class_by_map' );

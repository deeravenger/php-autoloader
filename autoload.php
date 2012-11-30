<?php

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

<?php
/**
 * ClassMap
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright (c) 2012, Dmitry Kuznetsov <dev.kuznetsov@gmail.com>. All rights reserved.
 * @author Dmitry Kuznetsov <dev.kuznetsov@gmail.com>
 * @url https://github.com/dmkuznetsov/php-class-map
*/
abstract class ClassMap_Progress
{
	abstract public function start( $count = null );

	abstract public function update( $number = 0 );

	abstract public function stop();

	/**
	 * @param string $name
	 * @throws Exception
	 * @return self
	 */
	public static function get( $name = 'None' )
	{
		$files = glob( dirname( __FILE__ ) . '/Progress/*.php' );
		foreach ( $files as $filePath )
		{
			$fileName = pathinfo( $filePath, PATHINFO_FILENAME );
			if ( strcasecmp( $name, $fileName ) == 0 )
			{
				$className = sprintf( 'ClassMap_Progress_%s', $name );
				require $filePath;
				return new $className();
			}
		}
		throw new Exception( 'Not found ClassMap_Log_' . $name );
	}
}
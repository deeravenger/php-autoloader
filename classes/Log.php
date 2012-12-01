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
 * @copyright (c) 2012, Dmitry Kuznetsov <kuznetsov2d@gmail.com>. All rights reserved.
 * @author Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @url https://github.com/dmkuznetsov/php-class-map
*/
abstract class ClassMap_Log
{
	/**
	 * @var bool
	 */
	protected $_verboseMode;

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
	abstract public function log();

	/**
	 * @param string $name
	 * @throws Exception
	 * @return self
	 */
	public static function get( $name = 'None' )
	{
		$files = glob( dirname( __FILE__ ) . '/Log/*.php' );
		foreach ( $files as $filePath )
		{
			$fileName = pathinfo( $filePath, PATHINFO_FILENAME );
			if ( strcasecmp( $name, $fileName ) == 0 )
			{
				$className = sprintf( 'ClassMap_Log_%s', $name );
				require $filePath;
				return new $className();
			}
		}
		throw new Exception( 'Not found ClassMap_Log_' . $name );
	}

	public function setVerbose( $verbose )
	{
		$this->_verboseMode = $verbose ? true : false;
	}
}
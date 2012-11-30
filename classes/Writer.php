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
class ClassMap_Writer
{
	/**
	 * @var array
	 */
	protected $_map;
	/**
	 * @var ClassMap_Log
	 */
	protected $_log;

	public function __construct( array $map, ClassMap_Log $log )
	{
		$this->_map = $map;
		$this->_log = $log;
	}

	public function save( $file )
	{
		$this->_checkFile( $file );
		$this->_createFile( $file );

		$this->_log->log( "Start writing class map to file..." );
		$success = $this->_writeToFile( $file );
		if ( $success )
		{
			$this->_log->log( "Success! Please, check file %s", $file );
		}
		else
		{
			$this->_log->log( "Error! Can't write to file %s", $file );
		}
	}

	protected function _checkFile( $file )
	{
		$this->_log->log( 'FILE: %s', $this->_getFileStatus( $file ) );
	}

	protected function _createFile( $file )
	{
	}

	protected function _writeToFile( $file )
	{
		$content = file_get_contents( dirname( __FILE__ ) . '/../autoload.php' );
		$content = str_replace( 'array()', var_export( $this->_map, true ), $content );
		$bytes = file_put_contents( $file, $content );
		return $bytes ? true : false;
	}

	private function _getFileStatus( $file )
	{
		if ( file_exists( $file ) )
		{
			if ( is_file( $file ) )
			{
				$status = 'found';
				if ( is_writable( $file ) )
				{
					$status .= ', writable';
				}
			}
			else
			{
				$status = 'is dir, not file';
			}
		}
		else
		{
			$status = 'not found';
			$dir = dirname( $file );
			if ( !is_dir( $dir ) )
			{
				$status .= ', parent dir not found';
			}
			else
			{
				if ( is_writable( $dir ) )
				{
					$status .= ', parent dir writable';
				}
				else
				{
					$status .= ', parent dir writable';
				}
			}
		}
		return $file . ' (' . $status . ')';
	}
}
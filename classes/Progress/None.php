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
class ClassMap_Progress_None extends ClassMap_Progress
{
	public function start( $count = null )
	{
	}

	public function update( $number = 0 )
	{
	}

	public function stop()
	{
	}
}
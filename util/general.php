<?php
/**
 * This file is part of SmartWork.
 *
 * SmartWork is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SmartWork is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SmartWork.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */

/**
 * Redirect to the given location
 *
 * @param string $location
 *
 * @return void
 * @deprecated since version 1.1
 */
function redirect($location)
{
    \SmartWork\Utility\General::redirect($location);
}

/**
 * Class loader
 *
 * @param string $name
 *
 * @return boolean
 * @deprecated since version 1.1
 */
function classLoad($name)
{
    \SmartWork\Utility\General::classLoad($name);
}
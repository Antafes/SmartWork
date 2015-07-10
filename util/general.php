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
 */
function redirect($location)
{
	header('Location: '.$location);
	die();
}

/**
 * Class loader
 *
 * @param string $name
 *
 * @return boolean
 */
function classLoad($name)
{
	$dir = __DIR__.'/../../Classes/';
	$pieces = explode('\\', $name);

	if ($pieces[0] === 'SmartWork')
	{
		$dir = __DIR__.'/../Classes/';
		array_shift($pieces);
	}

	$class = array_pop($pieces);

	if ($pieces)
	{
		$dir .= implode('/', $pieces) . '/';
	}

	if(file_exists($dir . $class . '.php'))
	{
		require_once($dir . $class . '.php');
		return true;
	}
	elseif ($name == 'Smarty')
	{
		require_once(__DIR__.'/../smarty3/Smarty.class.php');
		return true;
	}
	elseif ($name == 'PHPMailer')
	{
		require_once(__DIR__.'/../phpmailer/class.phpmailer.php');
		return true;
	}

	return false;
}